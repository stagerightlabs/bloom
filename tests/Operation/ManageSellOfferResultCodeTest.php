<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageSellOfferResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageSellOfferResultCode
 */
class ManageSellOfferResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0   => ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS,
            -1  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_MALFORMED,
            -2  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_TRUST,
            -3  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_TRUST,
            -4  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED,
            -5  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED,
            -6  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_LINE_FULL,
            -7  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_UNDERFUNDED,
            -8  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_CROSS_SELF,
            -9  => ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_ISSUER,
            -10 => ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_ISSUER,
            -11 => ManageSellOfferResultCode::MANAGE_SELL_OFFER_NOT_FOUND,
            -12 => ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE,
        ];
        $manageSellOfferResultCode = new ManageSellOfferResultCode();

        $this->assertEquals($expected, $manageSellOfferResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::success();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::malformed();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_MALFORMED, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNoTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_no_trust_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::sellNoTrust();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_TRUST, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNoTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_no_trust_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::buyNoTrust();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_TRUST, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNotAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_not_authorized_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::sellNotAuthorized();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNotAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_not_authorized_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::buyNotAuthorized();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::lineFull();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_LINE_FULL, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::underfunded();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_UNDERFUNDED, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::crossSelf
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cross_self_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::crossSelf();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_CROSS_SELF, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::sellNoIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_sell_no_issuer_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::sellNoIssuer();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_ISSUER, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::buyNoIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_buy_no_issuer_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::buyNoIssuer();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_ISSUER, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::notFound
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_found_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::notFound();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_NOT_FOUND, $manageSellOfferResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $manageSellOfferResultCode = ManageSellOfferResultCode::lowReserve();

        $this->assertInstanceOf(ManageSellOfferResultCode::class, $manageSellOfferResultCode);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE, $manageSellOfferResultCode->getType());
    }
}
