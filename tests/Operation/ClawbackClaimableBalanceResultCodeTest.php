<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResultCode
 */
class ClawbackClaimableBalanceResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS,
            -1 => ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST,
            -2 => ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER,
            -3 => ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED,
        ];
        $clawbackClaimableBalanceResultCode = new ClawbackClaimableBalanceResultCode();

        $this->assertEquals($expected, $clawbackClaimableBalanceResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $clawbackClaimableBalanceResultCode = ClawbackClaimableBalanceResultCode::success();

        $this->assertInstanceOf(ClawbackClaimableBalanceResultCode::class, $clawbackClaimableBalanceResultCode);
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS, $clawbackClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::doesNotExist
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_does_not_exist_type()
    {
        $clawbackClaimableBalanceResultCode = ClawbackClaimableBalanceResultCode::doesNotExist();

        $this->assertInstanceOf(ClawbackClaimableBalanceResultCode::class, $clawbackClaimableBalanceResultCode);
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST, $clawbackClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::notIssuer
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_issuer_type()
    {
        $clawbackClaimableBalanceResultCode = ClawbackClaimableBalanceResultCode::notIssuer();

        $this->assertInstanceOf(ClawbackClaimableBalanceResultCode::class, $clawbackClaimableBalanceResultCode);
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER, $clawbackClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::notClawbackEnabled
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_clawback_enabled_type()
    {
        $clawbackClaimableBalanceResultCode = ClawbackClaimableBalanceResultCode::notClawbackEnabled();

        $this->assertInstanceOf(ClawbackClaimableBalanceResultCode::class, $clawbackClaimableBalanceResultCode);
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED, $clawbackClaimableBalanceResultCode->getType());
    }
}
