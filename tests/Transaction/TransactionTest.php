<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\AccountMergeOp;
use StageRightLabs\Bloom\Operation\AllowTrustOp;
use StageRightLabs\Bloom\Operation\CreateAccountOp;
use StageRightLabs\Bloom\Operation\OperationList;
use StageRightLabs\Bloom\Operation\PaymentOp;
use StageRightLabs\Bloom\Operation\SetOptionsOp;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\Memo;
use StageRightLabs\Bloom\Transaction\OptionalTimeBounds;
use StageRightLabs\Bloom\Transaction\Preconditions;
use StageRightLabs\Bloom\Transaction\PreconditionsV2;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionExt;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\Transaction
 */
class TransactionTest extends TestCase
{
    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_created_for_an_addressable()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertTrue($sequenceNumber->isEqualTo($transaction->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_created_for_a_muxed_account()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertTrue($sequenceNumber->isEqualTo($transaction->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_created_with_a_fee()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber, UInt32::of(200));

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals(200, $transaction->getFee()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getOperationThreshold
     */
    public function it_can_return_the_maximum_threshold_for_its_operations()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operationList = OperationList::empty();
        $operationList = $operationList->push(AllowTrustOp::operation(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            'TEST',
            1
        )); // Low
        $transactionA = $transaction->withOperationList($operationList);
        $operationList = $operationList->push(PaymentOp::operation(
            'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            Asset::native(),
            '10'
        ));  // Medium
        $transactionB = $transaction->withOperationList($operationList);
        $operationList = $operationList->push(SetOptionsOp::operation()); // High
        $transactionC = $transaction->withOperationList($operationList);


        $this->assertEquals(Thresholds::CATEGORY_LOW, $transactionA->getOperationThreshold());
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, $transactionB->getOperationThreshold());
        $this->assertEquals(Thresholds::CATEGORY_HIGH, $transactionC->getOperationThreshold());
    }

    /**
     * @test
     * @covers ::getOperationThreshold
     */
    public function it_can_return_the_correct_threshold_for_account_merge_operations()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $operationList = OperationList::empty();
        $operationList = $operationList->push(AccountMergeOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'
        ));
        $transaction = $transaction->withOperationList($operationList);

        $this->assertEquals(Thresholds::CATEGORY_HIGH, $transaction->getOperationThreshold());
    }

    /**
     * @test
     * @covers ::getOperationThreshold
     */
    public function it_returns_a_null_threshold_if_no_operations_are_present()
    {
        $this->assertNull((new Transaction())->getOperationThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = Transaction::for($account, $sequenceNumber);
        $transaction = $transaction->withPreconditions(
            Preconditions::wrapTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'))
        );
        $buffer = XDR::fresh()->write($transaction);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_source_account_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withPreconditions($preconditions)
            ->withMemo($memo)
            ->withOperationList($operations)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_fee_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withSequenceNumber($sequenceNumber)
            ->withPreconditions($preconditions)
            ->withMemo($memo)
            ->withOperationList($operations)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_sequence_number_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withFee(UInt32::of(100))
            ->withPreconditions($preconditions)
            ->withMemo($memo)
            ->withOperationList($operations)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_time_bounds_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withMemo($memo)
            ->withOperationList($operations)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_memo_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $operations = OperationList::empty();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withPreconditions($preconditions)
            ->withOperationList($operations)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_operations_list_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $memo = Memo::none();
        $ext = TransactionExt::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withPreconditions($preconditions)
            ->withMemo($memo)
            ->withExtension($ext);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_ext_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $preconditions = Preconditions::wrapTimeBounds(
            OptionalTimeBounds::some(TimeBounds::oneYear())
        );
        $memo = Memo::none();
        $operations = OperationList::empty();
        $transaction = (new Transaction())
            ->withSourceAccount($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withPreconditions($preconditions)
            ->withMemo($memo)
            ->withOperationList($operations);

        XDR::fresh()->write($transaction);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transaction = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAZAAAAAAAAAABAAAAAQAAAABf7mYAAAAAAGHOSAAAAAAAAAAAAAAAAAA=')
            ->read(Transaction::class);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertTrue($transaction->getPreconditions()->getTimeBounds()->getMinTime()->isEqualTo(TimePoint::fromNativeString('2021-01-01')));
        $this->assertTrue($transaction->getPreconditions()->getTimeBounds()->getMaxTime()->isEqualTo(TimePoint::fromNativeString('2021-12-31')));
        $this->assertEquals(100, $transaction->getFee()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSourceAccount
     * @covers ::withSourceAccount
     */
    public function it_accepts_a_string_source_account()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $firstAccount = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $secondAccount = Account::fromAddress('GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4');
        $original = Transaction::for($firstAccount, $sequenceNumber);
        $modified = $original->withSourceAccount($secondAccount);

        $this->assertEquals($modified->getSourceAccount()->getAddress(), $secondAccount->getAddress());
    }

    /**
     * @test
     * @covers ::getSourceAccount
     * @covers ::withSourceAccount
     */
    public function it_accepts_a_muxed_account_source_account()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $firstAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $secondAccount = AccountId::fromAddressable('GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4');
        $original = Transaction::for($firstAccount, $sequenceNumber);
        $modified = $original->withSourceAccount($secondAccount);

        $this->assertEquals($modified->getSourceAccount()->getAddress(), $secondAccount->getAddress());
    }

    /**
     * @test
     * @covers ::getFee
     * @covers ::withFee
     */
    public function it_accepts_a_fee()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $modified = $original->withFee(UInt32::of(250));

        $this->assertInstanceOf(UInt32::class, $modified->getFee());
        $this->assertEquals(250, $modified->getFee()->toNativeInt());
        $this->assertNotEquals(250, $original->getFee()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSequenceNumber
     * @covers ::withSequenceNumber
     */
    public function it_accepts_a_sequence_number()
    {
        $firstSequenceNumber = SequenceNumber::of(1);
        $secondSequenceNumber = SequenceNumber::of(2);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $firstSequenceNumber);
        $modified = $original->withSequenceNumber($secondSequenceNumber);

        $this->assertInstanceOf(SequenceNumber::class, $modified->getSequenceNumber());
        $this->assertTrue($secondSequenceNumber->isEqualTo($modified->getSequenceNumber()));
        $this->assertFalse($secondSequenceNumber->isEqualTo($original->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::getPreconditions
     * @covers ::withPreconditions
     */
    public function it_accepts_time_bounds_as_preconditions()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $preconditions = Preconditions::wrapTimeBounds(
            TimeBounds::between('2021-01-01', '2021-12-31')
        );
        $modified = $original->withPreconditions($preconditions);

        $this->assertInstanceOf(Preconditions::class, $modified->getPreconditions());
        $this->assertEquals(1609459200, $modified->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
        $this->assertEquals(1640908800, $modified->getPreconditions()->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getPreconditions
     * @covers ::withPreconditions
     */
    public function it_accepts_a_preconditions_v2_set_as_preconditions()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $preconditionsV2 = (new PreconditionsV2())->withTimeBounds(
            TimeBounds::between('2021-01-01', '2021-12-31')
        );
        $preconditions = Preconditions::wrapPreconditionsV2($preconditionsV2);
        $modified = $original->withPreconditions($preconditions);

        $this->assertInstanceOf(Preconditions::class, $modified->getPreconditions());
        $this->assertEquals(1609459200, $modified->getPreconditions()->getTimeBounds()->getMinTime()->toNativeInt());
        $this->assertEquals(1640908800, $modified->getPreconditions()->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getPreconditions
     * @covers ::withPreconditions
     */
    public function it_accepts_a_none_preconditions_set_as_preconditions()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $preconditions = Preconditions::none();
        $modified = $original->withPreconditions($preconditions);

        $this->assertInstanceOf(Preconditions::class, $modified->getPreconditions());
        $this->assertNull($modified->getPreconditions()->getTimeBounds());
    }

    /**
     * @test
     * @covers ::getMemo
     * @covers ::withMemo
     */
    public function it_accepts_a_memo()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $modified = $original->withMemo(Memo::wrapText('Hello World'));

        $this->assertInstanceOf(Memo::class, $modified->getMemo());
        $this->assertEquals('Hello World', $modified->getMemo()->unwrap());
        $this->assertNull($original->getMemo()->unwrap());
    }

    /**
     * @test
     * @covers ::getOperationList
     * @covers ::withOperationList
     */
    public function it_accepts_an_operations_list()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $modified = $original->withOperationList(OperationList::empty());

        $this->assertInstanceOf(OperationList::class, $modified->getOperationList());
        $this->assertEquals(0, $modified->getOperationList()->count());
    }

    /**
     * @test
     * @covers ::getOperationCount
     * @covers ::withOperation
     */
    public function it_accepts_additional_operations()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            startingBalance: '10',
            source: $account,
        );
        $modified = $original->withOperation($operation);

        $this->assertInstanceOf(CreateAccountOp::class, $modified->getOperationList()->get(0)->getBody()->unwrap());
        $this->assertEquals(0, $original->getOperationCount());
        $this->assertEquals(1, $modified->getOperationCount());
    }

    /**
     * @test
     * @covers ::withOperation
     */
    public function it_automatically_increases_the_fee_if_needed()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber, UInt32::of(100));
        $operation = CreateAccountOp::operation(
            destination: 'GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW',
            startingBalance: '10',
            source: $account,
        );
        $modified = $original->withOperation($operation)->withOperation($operation);

        $this->assertEquals(200, $modified->getFee()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getExtension
     * @covers ::withExtension
     */
    public function it_returns_the_transaction_ext()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = Transaction::for($account, $sequenceNumber);
        $modified = $original->withExtension(TransactionExt::empty());

        $this->assertInstanceOf(TransactionExt::class, $modified->getExtension());
    }
}
