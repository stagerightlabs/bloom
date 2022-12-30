<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictSendResultCode
 */
class PathPaymentStrictSendResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0   => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS,
            -1  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED,
            -2  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED,
            -3  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST,
            -4  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED,
            -5  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION,
            -6  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_TRUST,
            -7  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED,
            -8  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_LINE_FULL,
            -9  => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_ISSUER,
            -10 => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS,
            -11 => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF,
            -12 => PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN,
        ];
        $pathPaymentStrictSendResultCode = new PathPaymentStrictSendResultCode();

        $this->assertEquals($expected, $pathPaymentStrictSendResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::success();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::malformed();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_underfunded_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::underfunded();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::srcNoTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_src_no_trust_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::srcNoTrust();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::srcNotAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_src_not_authorized_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::srcNotAuthorized();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::noDestination
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_destination_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::noDestination();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::noTrust();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_TRUST, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::notAuthorized();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::lineFull();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_LINE_FULL, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::noIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_issuer_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::noIssuer();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_ISSUER, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooFewOffers
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_too_few_offers_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::tooFewOffers();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::offerCrossSelf
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_offer_cross_self_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::offerCrossSelf();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF, $pathPaymentStrictSendResultCode->getType());
    }

    /**
     * @test
     * @covers ::underDestmin
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_over_sendmax_type()
    {
        $pathPaymentStrictSendResultCode = PathPaymentStrictSendResultCode::underDestmin();

        $this->assertInstanceOf(PathPaymentStrictSendResultCode::class, $pathPaymentStrictSendResultCode);
        $this->assertEquals(PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN, $pathPaymentStrictSendResultCode->getType());
    }
}
