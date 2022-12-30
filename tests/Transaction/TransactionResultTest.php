<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\ManageDataResult;
use StageRightLabs\Bloom\Operation\ManageDataResultCode;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\InnerTransactionResult;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultPair;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultResult;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\Bloom\Transaction\TransactionResultExt;
use StageRightLabs\Bloom\Transaction\TransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResult
 */
class TransactionResultTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $buffer = XDR::fresh()->write($transactionResult);

        $this->assertEquals('AAAAAAAAAAEAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_fee_charged_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $transactionResult = (new TransactionResult())
            ->withResult($transactionResultResult);
        XDR::fresh()->write($transactionResult);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_result_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $feeCharged = Int64::of(1);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged($feeCharged);
        XDR::fresh()->write($transactionResult);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $transactionResult = XDR::fromBase64('AAAAAAAAAAEAAAAAAAAAAAAAAAA=')
            ->read(TransactionResult::class);

        $this->assertInstanceOf(TransactionResult::class, $transactionResult);
        $this->assertInstanceOf(TransactionResultResult::class, $transactionResult->getResult());
        $this->assertInstanceOf(Int64::class, $transactionResult->getFeeCharged());
    }

    /**
     * @test
     * @covers ::withFeeCharged
     * @covers ::getFeeCharged
     */
    public function it_accepts_an_int64_fee_charged()
    {
        $transactionResult = (new TransactionResult())->withFeeCharged(Int64::of(1));
        $this->assertInstanceOf(Int64::class, $transactionResult->getFeeCharged());
    }

    /**
     * @test
     * @covers ::withFeeCharged
     * @covers ::getFeeCharged
     */
    public function it_accepts_a_scaled_amount_fee_charged()
    {
        $transactionResult = (new TransactionResult())->withFeeCharged(ScaledAmount::of(1));
        $this->assertInstanceOf(Int64::class, $transactionResult->getFeeCharged());
        $this->assertEquals('10000000', $transactionResult->getFeeCharged()->toNativeString());
    }

    /**
     * @test
     * @covers ::withResult
     * @covers ::getResult
     */
    public function it_accepts_a_result()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $transactionResult = (new TransactionResult())
            ->withResult($transactionResultResult);

        $this->assertInstanceOf(TransactionResult::class, $transactionResult);
        $this->assertInstanceOf(TransactionResultResult::class, $transactionResult->getResult());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_ext()
    {
        $transactionResult = (new TransactionResult())->withExtension(TransactionResultExt::empty());

        $this->assertInstanceOf(TransactionResult::class, $transactionResult);
        $this->assertInstanceOf(TransactionResultExt::class, $transactionResult->getExtension());
    }

    /**
     * @test
     * @covers ::getOperationResultList
     */
    public function it_returns_an_operation_result_list()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $feeCharged = Int64::of(1);
        $transactionResultA = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultB = new TransactionResult();

        $this->assertInstanceOf(OperationResultList::class, $transactionResultA->getOperationResultList());
        $this->assertInstanceOf(OperationResultList::class, $transactionResultB->getOperationResultList());
    }

    /**
     * @test
     * @covers ::getInnerTransactionResultPair
     */
    public function it_returns_an_inner_transaction_result_pair_if_available()
    {
        $hash = Hash::make('1');
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withTransactionHash($hash)
            ->withInnerTransactionResult($innerTransactionResult);
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulInnerTransactionResultPair($innerTransactionResultPair);
        $feeCharged = Int64::of(1);
        $transactionResultA = (new TransactionResult())
            ->withFeeCharged($feeCharged)
            ->withResult($transactionResultResult);
        $transactionResultB = new TransactionResult();

        $this->assertInstanceOf(InnerTransactionResultPair::class, $transactionResultA->getInnerTransactionResultPair());
        $this->assertNull($transactionResultB->getInnerTransactionResultPair());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList(OperationResultList::empty());
        $transactionResult = (new TransactionResult())
            ->withFeeCharged(Int64::of(1))
            ->withResult($transactionResultResult);

        $this->assertTrue($transactionResult->wasSuccessful());
        $this->assertFalse($transactionResult->wasNotSuccessful());
        $this->assertFalse((new TransactionResult())->wasSuccessful());
        $this->assertTrue((new TransactionResult())->wasNotSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $manageDataResult = ManageDataResult::simulate(ManageDataResultCode::lowReserve());
        $operationResultList = OperationResultList::of($manageDataResult);
        $transactionResultResult = (new TransactionResultResult())
            ->wrapFailedOperationResultList($operationResultList);
        $transactionResult = (new TransactionResult())
            ->withFeeCharged(Int64::of(1))
            ->withResult($transactionResultResult);

        $this->assertNotEmpty($transactionResult->getErrorMessage());
        $this->assertEquals('tx_failed', $transactionResult->getErrorCode());
        $this->assertNull((new TransactionResult())->getErrorMessage());
        $this->assertNull((new TransactionResult())->getErrorCode());
    }
}
