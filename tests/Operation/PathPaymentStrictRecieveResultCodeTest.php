<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\PathPaymentStrictReceiveResultCode
 */
class PathPaymentStrictReceiveResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0   => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS,
            -1  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED,
            -2  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED,
            -3  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST,
            -4  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED,
            -5  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION,
            -6  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST,
            -7  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED,
            -8  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL,
            -9  => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER,
            -10 => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS,
            -11 => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF,
            -12 => PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX,
        ];
        $memoType = new PathPaymentStrictReceiveResultCode();

        $this->assertEquals($expected, $memoType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::success();
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::success
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::success();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::malformed();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     */
    public function it_can_be_instantiated_as_a_underfunded_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::underfunded();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::sourceNoTrust
     */
    public function it_can_be_instantiated_as_a_source_no_trust_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::sourceNoTrust();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::sourceNotAuthorized
     */
    public function it_can_be_instantiated_as_a_source_not_authorized_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::sourceNotAuthorized();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::noDestination
     */
    public function it_can_be_instantiated_as_a_no_destination_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::noDestination();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::noTrust();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::notAuthorized();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::lineFull();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::noIssuer
     */
    public function it_can_be_instantiated_as_a_no_issuer_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::noIssuer();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooFewOffers
     */
    public function it_can_be_instantiated_as_a_too_few_offers_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::tooFewOffers();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::offerCrossesSelf
     */
    public function it_can_be_instantiated_as_a_offer_crosses_self_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::offerCrossesSelf();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF, $pathPaymentStrictReceiveResultCode->getType());
    }

    /**
     * @test
     * @covers ::overSendmax
     */
    public function it_can_be_instantiated_as_a_over_sendmax_type()
    {
        $pathPaymentStrictReceiveResultCode = PathPaymentStrictReceiveResultCode::overSendmax();

        $this->assertInstanceOf(PathPaymentStrictReceiveResultCode::class, $pathPaymentStrictReceiveResultCode);
        $this->assertEquals(PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX, $pathPaymentStrictReceiveResultCode->getType());
    }
}
