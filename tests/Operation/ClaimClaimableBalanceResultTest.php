<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResult
 */
class ClaimClaimableBalanceResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClaimClaimableBalanceResultCode::class, ClaimClaimableBalanceResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS        => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM   => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_LINE_FULL      => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NO_TRUST       => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED => XDR::VOID,
        ];

        $this->assertEquals($expected, ClaimClaimableBalanceResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $claimClaimableBalanceResult = ClaimClaimableBalanceResult::success();
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS, $claimClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ClaimClaimableBalanceResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $claimClaimableBalanceResult = ClaimClaimableBalanceResult::simulate(ClaimClaimableBalanceResultCode::doesNotExist());

        $this->assertInstanceOf(ClaimClaimableBalanceResult::class, $claimClaimableBalanceResult);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST, $claimClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $claimClaimableBalanceResultA = ClaimClaimableBalanceResult::success();
        $claimClaimableBalanceResultB = new ClaimClaimableBalanceResult();

        $this->assertTrue($claimClaimableBalanceResultA->wasSuccessful());
        $this->assertFalse($claimClaimableBalanceResultA->wasNotSuccessful());
        $this->assertTrue($claimClaimableBalanceResultB->wasNotSuccessful());
        $this->assertFalse($claimClaimableBalanceResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $claimClaimableBalanceResult = ClaimClaimableBalanceResult::simulate(ClaimClaimableBalanceResultCode::doesNotExist());

        $this->assertNotEmpty($claimClaimableBalanceResult->getErrorMessage());
        $this->assertEquals('claim_claimable_balance_does_not_exist', $claimClaimableBalanceResult->getErrorCode());
        $this->assertNull((new ClaimClaimableBalanceResult())->getErrorMessage());
        $this->assertNull((new ClaimClaimableBalanceResult())->getErrorCode());
    }
}
