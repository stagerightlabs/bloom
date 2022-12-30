<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\OperationResult;
use StageRightLabs\Bloom\Operation\OperationResultCode;
use StageRightLabs\Bloom\Operation\OperationResultTr;
use StageRightLabs\Bloom\Operation\PaymentResult;
use StageRightLabs\Bloom\Operation\PaymentResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\OperationResult
 */
class OperationResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(OperationResultCode::class, OperationResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            OperationResultCode::INNER               => OperationResultTr::class,
            OperationResultCode::BAD_AUTH            => XDR::VOID,
            OperationResultCode::NO_ACCOUNT          => XDR::VOID,
            OperationResultCode::NOT_SUPPORTED       => XDR::VOID,
            OperationResultCode::TOO_MANY_SUBENTRIES => XDR::VOID,
            OperationResultCode::EXCEEDED_WORK_LIMIT => XDR::VOID,
            OperationResultCode::TOO_MANY_SPONSORING => XDR::VOID,
        ];

        $this->assertEquals($expected, OperationResult::arms());
    }

    /**
     * @test
     * @covers ::wrapOperationResultTr
     * @covers ::getType
     * @covers ::unwrap
     */
    public function it_can_wrap_an_operation_result_tr()
    {
        $paymentResult = PaymentResult::success();
        $operationResultTr = OperationResultTr::wrapPaymentResult($paymentResult);
        $operationResult = OperationResult::wrapOperationResultTr($operationResultTr);

        $this->assertInstanceOf(OperationResultTr::class, $operationResult->unwrap());
        $this->assertEquals(OperationResultCode::INNER, $operationResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_type_is_set()
    {
        $this->assertNull((new OperationResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $operationResult = OperationResult::simulate(OperationResultCode::badAuth());

        $this->assertInstanceOf(OperationResult::class, $operationResult);
        $this->assertEquals(OperationResultCode::BAD_AUTH, $operationResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $paymentResult = PaymentResult::success();
        $operationResultTr = OperationResultTr::wrapPaymentResult($paymentResult);
        $operationResultA = OperationResult::wrapOperationResultTr($operationResultTr);
        $operationResultB = new OperationResult();

        $this->assertTrue($operationResultA->wasSuccessful());
        $this->assertFalse($operationResultA->wasNotSuccessful());
        $this->assertTrue($operationResultB->wasNotSuccessful());
        $this->assertFalse($operationResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $paymentResult = PaymentResult::simulate(PaymentResultCode::malformed());
        $operationResultTr = OperationResultTr::wrapPaymentResult($paymentResult);
        $operationResult = OperationResult::wrapOperationResultTr($operationResultTr);
        $badAuthOperationResult = OperationResult::simulate(OperationResultCode::badAuth());

        $this->assertNotEmpty($operationResult->getErrorMessage());
        $this->assertEquals('payment_malformed', $operationResult->getErrorCode());
        $this->assertNotEmpty($badAuthOperationResult->getErrorMessage());
        $this->assertEquals('op_bad_auth', $badAuthOperationResult->getErrorCode());
        $this->assertNull((new OperationResult())->getErrorMessage());
        $this->assertNull((new OperationResult())->getErrorCode());
    }
}
