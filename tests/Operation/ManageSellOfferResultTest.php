<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageOfferSuccessResult;
use StageRightLabs\Bloom\Operation\ManageSellOfferResult;
use StageRightLabs\Bloom\Operation\ManageSellOfferResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageSellOfferResult
 */
class ManageSellOfferResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ManageSellOfferResultCode::class, ManageSellOfferResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS             => ManageOfferSuccessResult::class,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_MALFORMED           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_TRUST       => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_TRUST        => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED  => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_LINE_FULL           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_UNDERFUNDED         => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_CROSS_SELF          => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_ISSUER      => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_ISSUER       => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_NOT_FOUND           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE         => XDR::VOID,
        ];

        $this->assertEquals($expected, ManageSellOfferResult::arms());
    }

    /**
     * @test
     * @covers ::wrapManageOfferSuccessResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_manage_offer_result_success()
    {
        $manageSellOfferResult = ManageSellOfferResult::wrapManageOfferSuccessResult(new ManageOfferSuccessResult());

        $this->assertInstanceOf(ManageSellOfferResult::class, $manageSellOfferResult);
        $this->assertInstanceOf(ManageOfferSuccessResult::class, $manageSellOfferResult->unwrap());
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS, $manageSellOfferResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ManageSellOfferResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $manageSellOfferResult = ManageSellOfferResult::simulate(ManageSellOfferResultCode::lowReserve());

        $this->assertInstanceOf(ManageSellOfferResult::class, $manageSellOfferResult);
        $this->assertEquals(ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE, $manageSellOfferResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $manageSellOfferResultA = ManageSellOfferResult::wrapManageOfferSuccessResult(new ManageOfferSuccessResult());
        $manageSellOfferResultB = new ManageSellOfferResult();

        $this->assertTrue($manageSellOfferResultA->wasSuccessful());
        $this->assertFalse($manageSellOfferResultA->wasNotSuccessful());
        $this->assertTrue($manageSellOfferResultB->wasNotSuccessful());
        $this->assertFalse($manageSellOfferResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $manageSellOfferResult = ManageSellOfferResult::simulate(ManageSellOfferResultCode::lowReserve());

        $this->assertNotEmpty($manageSellOfferResult->getErrorMessage());
        $this->assertEquals('manage_sell_offer_low_reserve', $manageSellOfferResult->getErrorCode());
        $this->assertNull((new ManageSellOfferResult())->getErrorMessage());
        $this->assertNull((new ManageSellOfferResult())->getErrorCode());
    }
}
