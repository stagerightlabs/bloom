<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\PaymentResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PaymentResultCode
 */
class PaymentResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => PaymentResultCode::PAYMENT_SUCCESS,
            -1 => PaymentResultCode::PAYMENT_MALFORMED,
            -2 => PaymentResultCode::PAYMENT_UNDERFUNDED,
            -3 => PaymentResultCode::PAYMENT_SRC_NO_TRUST,
            -4 => PaymentResultCode::PAYMENT_SRC_NOT_AUTHORIZED,
            -5 => PaymentResultCode::PAYMENT_NO_DESTINATION,
            -6 => PaymentResultCode::PAYMENT_NO_TRUST,
            -7 => PaymentResultCode::PAYMENT_NOT_AUTHORIZED,
            -8 => PaymentResultCode::PAYMENT_LINE_FULL,
            -9 => PaymentResultCode::PAYMENT_NO_ISSUER,
        ];
        $paymentResultCode = new PaymentResultCode();

        $this->assertEquals($expected, $paymentResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $paymentResultCodeType = PaymentResultCode::success();
        $this->assertEquals(PaymentResultCode::PAYMENT_SUCCESS, $paymentResultCodeType->getType());
    }

    /**
     * @test
     * @covers ::success
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $paymentResultCode = PaymentResultCode::success();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_SUCCESS, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $paymentResultCode = PaymentResultCode::malformed();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_MALFORMED, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $paymentResultCode = PaymentResultCode::underfunded();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_UNDERFUNDED, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::sourceNoTrust
     */
    public function it_can_be_instantiated_as_a_source_no_trust_type()
    {
        $paymentResultCode = PaymentResultCode::sourceNoTrust();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_SRC_NO_TRUST, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::sourceNotAuthorized
     */
    public function it_can_be_instantiated_as_a_source_not_authorized_type()
    {
        $paymentResultCode = PaymentResultCode::sourceNotAuthorized();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_SRC_NOT_AUTHORIZED, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::noDestination
     */
    public function it_can_be_instantiated_as_a_no_destination_type()
    {
        $paymentResultCode = PaymentResultCode::noDestination();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_NO_DESTINATION, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $paymentResultCode = PaymentResultCode::noTrust();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_NO_TRUST, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $paymentResultCode = PaymentResultCode::notAuthorized();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_NOT_AUTHORIZED, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $paymentResultCode = PaymentResultCode::lineFull();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_LINE_FULL, $paymentResultCode->getType());
    }

    /**
     * @test
     * @covers ::noIssuer
     */
    public function it_can_be_instantiated_as_a_no_issuer_type()
    {
        $paymentResultCode = PaymentResultCode::noIssuer();

        $this->assertInstanceOf(PaymentResultCode::class, $paymentResultCode);
        $this->assertEquals(PaymentResultCode::PAYMENT_NO_ISSUER, $paymentResultCode->getType());
    }
}
