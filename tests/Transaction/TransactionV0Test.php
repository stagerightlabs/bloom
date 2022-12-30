<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Cryptography\ED25519;
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
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\Bloom\Transaction\TransactionV0Ext;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionV0
 */
class TransactionV0Test extends TestCase
{
    /**
     * @test
     * @covers ::for
     */
    public function it_can_be_created_for_an_addressable()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);

        $this->assertInstanceOf(TransactionV0::class, $transaction);
        $this->assertTrue($sequenceNumber->isEqualTo($transaction->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::getOperationThreshold
     */
    public function it_can_return_the_maximum_threshold_for_its_operations()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);
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
        $transaction = TransactionV0::for($account, $sequenceNumber);
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
        $this->assertNull((new TransactionV0())->getOperationThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $transaction = TransactionV0::for($account, $sequenceNumber);
        $transaction = $transaction->withTimeBounds(TimeBounds::between('2021-01-01', '2021-12-31'));
        $buffer = XDR::fresh()->write($transaction);

        $this->assertEquals('am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAABkAAAAAAAAAAEAAAABAAAAAF/uZgAAAAAAYc5IAAAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_source_account_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $sequenceNumber = SequenceNumber::of(1);
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withTimeBounds($optionalTimeBounds)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
            ->withSequenceNumber($sequenceNumber)
            ->withTimeBounds($optionalTimeBounds)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
            ->withFee(UInt32::of(100))
            ->withTimeBounds($optionalTimeBounds)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $memo = Memo::none();
        $operations = OperationList::empty();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $operations = OperationList::empty();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withTimeBounds($optionalTimeBounds)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $memo = Memo::none();
        $ext = TransactionV0Ext::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withTimeBounds($optionalTimeBounds)
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
        $account = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $memo = Memo::none();
        $operations = OperationList::empty();
        $transaction = (new TransactionV0())
            ->withSourceAccountEd25519($account)
            ->withFee(UInt32::of(100))
            ->withSequenceNumber($sequenceNumber)
            ->withTimeBounds($optionalTimeBounds)
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
        $transaction = XDR::fromBase64('am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAABkAAAAAAAAAAEAAAABAAAAAF/uZgAAAAAAYc5IAAAAAAAAAAAAAAAAAA==')
            ->read(TransactionV0::class);

        $this->assertInstanceOf(TransactionV0::class, $transaction);
        $this->assertTrue($transaction->getTimeBounds()->getMinTime()->isEqualTo(TimePoint::fromNativeString('2021-01-01')));
        $this->assertTrue($transaction->getTimeBounds()->getMaxTime()->isEqualTo(TimePoint::fromNativeString('2021-12-31')));
        $this->assertEquals(100, $transaction->getFee()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getSourceAccountEd25519
     * @covers ::withSourceAccountEd25519
     */
    public function it_accepts_a_source_account()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $firstAccount = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $secondAccount = ED25519::fromAddress('GAAYP5PGGRZLM5OQIPI4LJNENVJY4FFMKZW2U5IEKPAHS362B6G2XTG4');
        $original = TransactionV0::for($firstAccount, $sequenceNumber);
        $modified = $original->withSourceAccountEd25519($secondAccount);

        $this->assertEquals($modified->getSourceAccountEd25519()->getBytes(), $secondAccount->getBytes());
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
        $original = TransactionV0::for($account, $sequenceNumber);
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
        $original = TransactionV0::for($account, $firstSequenceNumber);
        $modified = $original->withSequenceNumber($secondSequenceNumber);

        $this->assertInstanceOf(SequenceNumber::class, $modified->getSequenceNumber());
        $this->assertTrue($secondSequenceNumber->isEqualTo($modified->getSequenceNumber()));
        $this->assertFalse($secondSequenceNumber->isEqualTo($original->getSequenceNumber()));
    }

    /**
     * @test
     * @covers ::hasTimeBounds
     */
    public function it_knows_when_time_bounds_have_been_applied()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = TransactionV0::for($account, $sequenceNumber);
        $emptyTimeBounds = OptionalTimeBounds::none();
        $modified = $original->withTimeBounds($emptyTimeBounds);

        $this->assertFalse($modified->hasTimeBounds());

        $oneYear = TimeBounds::oneYear();
        $modified = $modified->withTimeBounds($oneYear);

        $this->assertTrue($modified->hasTimeBounds());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     * @covers ::withTimeBounds
     */
    public function it_accepts_time_bounds()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = TransactionV0::for($account, $sequenceNumber);
        $timeBounds = TimeBounds::between('2021-01-01', '2021-12-31');
        $modified = $original->withTimeBounds($timeBounds);

        $this->assertInstanceOf(TimeBounds::class, $modified->getTimeBounds());
        $this->assertEquals(1609459200, $modified->getTimeBounds()->getMinTime()->toNativeInt());
        $this->assertEquals(1640908800, $modified->getTimeBounds()->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     * @covers ::withTimeBounds
     */
    public function it_accepts_optional_time_bounds()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = TransactionV0::for($account, $sequenceNumber);
        $modified = $original->withTimeBounds(OptionalTimeBounds::none());

        $this->assertFalse($modified->hasTimeBounds());
    }

    /**
     * @test
     * @covers ::getTimeBounds
     * @covers ::withTimeBounds
     */
    public function it_accepts_null_as_a_time_bounds_value()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $account = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $original = TransactionV0::for($account, $sequenceNumber);
        $modified = $original->withTimeBounds(null);

        $this->assertFalse($modified->hasTimeBounds());
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
        $original = TransactionV0::for($account, $sequenceNumber);
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
        $original = TransactionV0::for($account, $sequenceNumber);
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
        $original = TransactionV0::for($account, $sequenceNumber);
        $operation = CreateAccountOp::operation(
            destination: $account,
            startingBalance: '10',
        );
        $modified = $original->withOperation($operation);

        $this->assertInstanceOf(CreateAccountOp::class, $modified->getOperationList()->get(0)->getBody()->unwrap());
        $this->assertEquals(0, $original->getOperationCount());
        $this->assertEquals(1, $modified->getOperationCount());
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
        $original = TransactionV0::for($account, $sequenceNumber);
        $modified = $original->withExtension(TransactionV0Ext::empty());

        $this->assertInstanceOf(TransactionV0Ext::class, $modified->getExtension());
    }
}
