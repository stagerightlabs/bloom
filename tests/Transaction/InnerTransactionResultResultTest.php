<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\InnerTransactionResultResult;
use StageRightLabs\Bloom\Transaction\TransactionResultCode;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\InnerTransactionResultResult
 */
class InnerTransactionResultResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(TransactionResultCode::class, InnerTransactionResultResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
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
            TransactionResultCode::BAD_SPONSORSHIP        => XDR::VOID,
            TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP => XDR::VOID,
            TransactionResultCode::MALFORMED              => XDR::VOID,
        ];

        $this->assertEquals($expected, InnerTransactionResultResult::arms());
    }

    /**
     * @test
     * @covers ::wrapSuccessfulOperationResultList
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_list_of_successful_operation_results()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapSuccessfulOperationResultList($operationResultList);

        $this->assertInstanceOf(OperationResultList::class, $innerTransactionResultResult->unwrap());
        $this->assertEquals(TransactionResultCode::SUCCESS, $innerTransactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::wrapFailedOperationResultList
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_list_of_failed_operation_results()
    {
        $operationResultList = OperationResultList::empty();
        $innerTransactionResultResult = InnerTransactionResultResult::wrapFailedOperationResultList($operationResultList);

        $this->assertInstanceOf(OperationResultList::class, $innerTransactionResultResult->unwrap());
        $this->assertEquals(TransactionResultCode::FAILED, $innerTransactionResultResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function the_type_is_null_if_no_operation_list_is_present()
    {
        $this->assertNull((new InnerTransactionResultResult())->getType());
    }
}
