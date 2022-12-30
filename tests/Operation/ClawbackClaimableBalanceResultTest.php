<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceResult
 */
class ClawbackClaimableBalanceResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClawbackClaimableBalanceResultCode::class, ClawbackClaimableBalanceResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS              => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST       => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER           => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED => XDR::VOID,
        ];

        $this->assertEquals($expected, ClawbackClaimableBalanceResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $clawbackClaimableBalanceResult = ClawbackClaimableBalanceResult::success();
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS, $clawbackClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ClawbackClaimableBalanceResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $clawbackClaimableBalanceResult = ClawbackClaimableBalanceResult::simulate(ClawbackClaimableBalanceResultCode::doesNotExist());

        $this->assertInstanceOf(ClawbackClaimableBalanceResult::class, $clawbackClaimableBalanceResult);
        $this->assertEquals(ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST, $clawbackClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $clawbackClaimableBalanceResultA = ClawbackClaimableBalanceResult::success();
        $clawbackClaimableBalanceResultB = new ClawbackClaimableBalanceResult();

        $this->assertTrue($clawbackClaimableBalanceResultA->wasSuccessful());
        $this->assertFalse($clawbackClaimableBalanceResultA->wasNotSuccessful());
        $this->assertTrue($clawbackClaimableBalanceResultB->wasNotSuccessful());
        $this->assertFalse($clawbackClaimableBalanceResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $clawbackClaimableBalanceResult = ClawbackClaimableBalanceResult::simulate(ClawbackClaimableBalanceResultCode::doesNotExist());

        $this->assertNotEmpty($clawbackClaimableBalanceResult->getErrorMessage());
        $this->assertEquals('clawback_claimable_balance_does_not_exist', $clawbackClaimableBalanceResult->getErrorCode());
        $this->assertNull((new ClawbackClaimableBalanceResult())->getErrorMessage());
        $this->assertNull((new ClawbackClaimableBalanceResult())->getErrorCode());
    }
}
