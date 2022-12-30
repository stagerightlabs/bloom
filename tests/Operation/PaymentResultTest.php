<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\PaymentResult;
use StageRightLabs\Bloom\Operation\PaymentResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PaymentResult
 */
class PaymentResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(PaymentResultCode::class, PaymentResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            PaymentResultCode::PAYMENT_SUCCESS            => XDR::VOID,
            PaymentResultCode::PAYMENT_MALFORMED          => XDR::VOID,
            PaymentResultCode::PAYMENT_UNDERFUNDED        => XDR::VOID,
            PaymentResultCode::PAYMENT_SRC_NO_TRUST       => XDR::VOID,
            PaymentResultCode::PAYMENT_SRC_NOT_AUTHORIZED => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_DESTINATION     => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_TRUST           => XDR::VOID,
            PaymentResultCode::PAYMENT_NOT_AUTHORIZED     => XDR::VOID,
            PaymentResultCode::PAYMENT_LINE_FULL          => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_ISSUER          => XDR::VOID,
        ];

        $this->assertEquals($expected, PaymentResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_result()
    {
        $paymentResult = PaymentResult::success();

        $this->assertInstanceOf(PaymentResult::class, $paymentResult);
        $this->assertNull($paymentResult->unwrap());
        $this->assertEquals(PaymentResultCode::PAYMENT_SUCCESS, $paymentResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new PaymentResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $paymentResult = PaymentResult::simulate(PaymentResultCode::malformed());

        $this->assertInstanceOf(PaymentResult::class, $paymentResult);
        $this->assertEquals(PaymentResultCode::PAYMENT_MALFORMED, $paymentResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $paymentResultA = PaymentResult::success();
        $paymentResultB = new PaymentResult();

        $this->assertTrue($paymentResultA->wasSuccessful());
        $this->assertFalse($paymentResultA->wasNotSuccessful());
        $this->assertTrue($paymentResultB->wasNotSuccessful());
        $this->assertFalse($paymentResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $paymentResult = PaymentResult::simulate(PaymentResultCode::malformed());

        $this->assertNotEmpty($paymentResult->getErrorMessage());
        $this->assertEquals('payment_malformed', $paymentResult->getErrorCode());
        $this->assertNull((new PaymentResult())->getErrorMessage());
        $this->assertNull((new PaymentResult())->getErrorCode());
    }
}
