<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageBuyOfferResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageBuyOfferResultCode
 */
class ManageBuyOfferResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0   => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SUCCESS,
            -1  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_MALFORMED,
            -2  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_TRUST,
            -3  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_TRUST,
            -4  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED,
            -5  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED,
            -6  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LINE_FULL,
            -7  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_UNDERFUNDED,
            -8  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_CROSS_SELF,
            -9  => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_ISSUER,
            -10 => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_ISSUER,
            -11 => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_NOT_FOUND,
            -12 => ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LOW_RESERVE,
        ];
        $manageBuyOfferResultCode = new ManageBuyOfferResultCode();

        $this->assertEquals($expected, $manageBuyOfferResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::success();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SUCCESS, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::malformed();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_MALFORMED, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNoTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_no_trust_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::sellNoTrust();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_TRUST, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNoTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_no_trust_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::buyNoTrust();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_TRUST, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNotAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_ot_authorized_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::sellNotAuthorized();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNotAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_not_authorized_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::buyNotAuthorized();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::lineFull();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LINE_FULL, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::underfunded();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_UNDERFUNDED, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::crossSelf
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cross_self_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::crossSelf();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_CROSS_SELF, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNoIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_no_issuer_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::sellNoIssuer();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_ISSUER, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNoIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_no_issuer_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::buyNoIssuer();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_ISSUER, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::notFound
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_found_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::notFound();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_NOT_FOUND, $manageBuyOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $manageBuyOfferResultCode = ManageBuyOfferResultCode::lowReserve();

        $this->assertInstanceOf(ManageBuyOfferResultCode::class, $manageBuyOfferResultCode);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LOW_RESERVE, $manageBuyOfferResultCode->getType());
    }
}
