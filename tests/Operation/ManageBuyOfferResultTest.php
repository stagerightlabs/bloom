<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ManageBuyOfferResult;
use StageRightLabs\Bloom\Operation\ManageBuyOfferResultCode;
use StageRightLabs\Bloom\Operation\ManageOfferSuccessResult;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ManageBuyOfferResult
 */
class ManageBuyOfferResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ManageBuyOfferResultCode::class, ManageBuyOfferResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SUCCESS             => ManageOfferSuccessResult::class,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_MALFORMED           => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_TRUST       => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_TRUST        => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED  => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LINE_FULL           => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_UNDERFUNDED         => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_CROSS_SELF          => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SELL_NO_ISSUER      => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_BUY_NO_ISSUER       => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_NOT_FOUND           => XDR::VOID,
            ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LOW_RESERVE         => XDR::VOID,
        ];

        $this->assertEquals($expected, ManageBuyOfferResult::arms());
    }

    /**
     * @test
     * @covers ::wrapManageOfferSuccessResult
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_manage_offer_success_result()
    {
        $manageBuyOfferResult = ManageBuyOfferResult::wrapManageOfferSuccessResult(
            new ManageOfferSuccessResult()
        );

        $this->assertInstanceOf(ManageBuyOfferResult::class, $manageBuyOfferResult);
        $this->assertInstanceOf(ManageOfferSuccessResult::class, $manageBuyOfferResult->unwrap());
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_SUCCESS, $manageBuyOfferResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ManageBuyOfferResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $manageBuyOfferResult = ManageBuyOfferResult::simulate(ManageBuyOfferResultCode::lineFull());

        $this->assertInstanceOf(ManageBuyOfferResult::class, $manageBuyOfferResult);
        $this->assertEquals(ManageBuyOfferResultCode::MANAGE_BUY_OFFER_LINE_FULL, $manageBuyOfferResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $manageBuyOfferResultA = ManageBuyOfferResult::wrapManageOfferSuccessResult(
            new ManageOfferSuccessResult()
        );
        $manageBuyOfferResultB = new ManageBuyOfferResult();

        $this->assertTrue($manageBuyOfferResultA->wasSuccessful());
        $this->assertFalse($manageBuyOfferResultA->wasNotSuccessful());
        $this->assertTrue($manageBuyOfferResultB->wasNotSuccessful());
        $this->assertFalse($manageBuyOfferResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $manageBuyOfferResult = ManageBuyOfferResult::simulate(ManageBuyOfferResultCode::lineFull());

        $this->assertNotEmpty($manageBuyOfferResult->getErrorMessage());
        $this->assertEquals('manage_buy_offer_line_full', $manageBuyOfferResult->getErrorCode());
        $this->assertNull((new ManageBuyOfferResult())->getErrorMessage());
        $this->assertNull((new ManageBuyOfferResult())->getErrorCode());
    }
}
