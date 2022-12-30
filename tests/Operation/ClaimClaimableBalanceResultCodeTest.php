<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClaimClaimableBalanceResultCode
 */
class ClaimClaimableBalanceResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS,
            -1 => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST,
            -2 => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM,
            -3 => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_LINE_FULL,
            -4 => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NO_TRUST,
            -5 => ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED,
        ];
        $claimClaimableBalanceResultCode = new ClaimClaimableBalanceResultCode();

        $this->assertEquals($expected, $claimClaimableBalanceResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::success();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS, $claimClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::doesNotExist
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_does_not_exist_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::doesNotExist();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST, $claimClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::cannotClaim
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cannot_claim_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::cannotClaim();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM, $claimClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::lineFull();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_LINE_FULL, $claimClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::noTrust();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NO_TRUST, $claimClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $claimClaimableBalanceResultCode = ClaimClaimableBalanceResultCode::notAuthorized();

        $this->assertInstanceOf(ClaimClaimableBalanceResultCode::class, $claimClaimableBalanceResultCode);
        $this->assertEquals(ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED, $claimClaimableBalanceResultCode->getType());
    }
}
