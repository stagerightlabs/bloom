<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\InnerTransactionResult;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultPair;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultResult;
use StageRightLabs\Bloom\Transaction\TransactionResultCode;
use StageRightLabs\Bloom\Transaction\TransactionResultResult;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TransactionResultResult
 */
class TransactionResultResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(TransactionResultCode::class, TransactionResultResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            TransactionResultCode::FEE_BUMP_INNER_SUCCESS => InnerTransactionResultPair::class,
            TransactionResultCode::SUCCESS                => OperationResultList::class,
            TransactionResultCode::FAILED                 => OperationResultList::class,
            TransactionResultCode::TOO_EARLY              => XDR::VOID,
            TransactionResultCode::TOO_LATE               => XDR::VOID,
            TransactionResultCode::MISSING_OPERATION      => XDR::VOID,
            TransactionResultCode::BAD_SEQ                => XDR::VOID,
            TransactionResultCode::BAD_AUTH               => XDR::VOID,
            TransactionResultCode::INSUFFICIENT_BALANCE   => XDR::VOID,
            TransactionResultCode::NO_ACCOUNT             => XDR::VOID,
            TransactionResultCode::INSUFFICIENT_FEE       => XDR::VOID,
            TransactionResultCode::BAD_AUTH_EXTRA         => XDR::VOID,
            TransactionResultCode::INTERNAL_ERROR         => XDR::VOID,
            TransactionResultCode::NOT_SUPPORTED          => XDR::VOID,
            TransactionResultCode::FEE_BUMP_INNER_FAILED  => InnerTransactionResultPair::class,
            TransactionResultCode::BAD_SPONSORSHIP        => XDR::VOID,
            TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP => XDR::VOID,
            TransactionResultCode::MALFORMED              => XDR::VOID,
        ];

        $this->assertEquals($expected, TransactionResultResult::arms());
    }

    /**
     * @test
     * @covers ::wrapSuccessfulInnerTransactionResultPair
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_successful_inner_transaction_result_pair()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withInnerTransactionResult($innerTransactionResult);
        $transactionResultResult = (new TransactionResultResult())->wrapSuccessfulInnerTransactionResultPair($innerTransactionResultPair);

        $this->assertInstanceOf(TransactionResultResult::class, $transactionResultResult);
        $this->assertInstanceOf(InnerTransactionResultPair::class, $transactionResultResult->unwrap());
        $this->assertEquals('txFeeBumpInnerSuccess', $transactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::wrapFailedInnerTransactionResultPair
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_failed_inner_transaction_result_pair()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapFailedOperationResultList($operationResultList);
        $innerTransactionResult = (new InnerTransactionResult())->withResult($innerTransactionResultResult);
        $innerTransactionResultPair = (new InnerTransactionResultPair())
            ->withInnerTransactionResult($innerTransactionResult);
        $transactionResultResult = (new TransactionResultResult())->wrapFailedInnerTransactionResultPair($innerTransactionResultPair);

        $this->assertInstanceOf(TransactionResultResult::class, $transactionResultResult);
        $this->assertInstanceOf(InnerTransactionResultPair::class, $transactionResultResult->unwrap());
        $this->assertEquals('txFeeBumpInnerFailed', $transactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::wrapSuccessfulOperationResultList
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_successful_operation_result_list()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);

        $this->assertInstanceOf(TransactionResultResult::class, $transactionResultResult);
        $this->assertInstanceOf(OperationResultList::class, $transactionResultResult->unwrap());
        $this->assertEquals('txSuccess', $transactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::wrapFailedOperationResultList
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_failed_operation_result_list()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResult = (new TransactionResultResult())
            ->wrapFailedOperationResultList($operationResultList);

        $this->assertInstanceOf(TransactionResultResult::class, $transactionResultResult);
        $this->assertInstanceOf(OperationResultList::class, $transactionResultResult->unwrap());
        $this->assertEquals('txFailed', $transactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function the_type_is_null_if_no_operation_list_is_present()
    {
        $this->assertNull((new TransactionResultResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $transactionResultResult = TransactionResultResult::simulate(TransactionResultCode::tooEarly());

        $this->assertInstanceOf(TransactionResultResult::class, $transactionResultResult);
        $this->assertEquals(TransactionResultCode::TOO_EARLY, $transactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $operationResultList = OperationResultList::empty();
        $transactionResultResultA = (new TransactionResultResult())
            ->wrapSuccessfulOperationResultList($operationResultList);
        $transactionResultResultB = (new TransactionResultResult())
            ->wrapFailedOperationResultList($operationResultList);

        $this->assertTrue($transactionResultResultA->wasSuccessful());
        $this->assertFalse($transactionResultResultA->wasNotSuccessful());
        $this->assertTrue($transactionResultResultB->wasNotSuccessful());
        $this->assertFalse($transactionResultResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $transactionResultResult = TransactionResultResult::simulate(TransactionResultCode::tooEarly());

        $this->assertNotEmpty($transactionResultResult->getErrorMessage());
        $this->assertEquals('tx_too_early', $transactionResultResult->getErrorCode());
        $this->assertNull((new TransactionResultResult())->getErrorMessage());
        $this->assertNull((new TransactionResultResult())->getErrorCode());
    }
}
