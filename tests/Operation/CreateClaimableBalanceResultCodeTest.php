<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\CreateClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateClaimableBalanceResultCode
 */
class CreateClaimableBalanceResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS,
            -1 => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED,
            -2 => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE,
            -3 => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NO_TRUST,
            -4 => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED,
            -5 => CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED,
        ];
        $createClaimableBalanceResultCode = new CreateClaimableBalanceResultCode();

        $this->assertEquals($expected, $createClaimableBalanceResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::success();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS, $createClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::malformed();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED, $createClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::lowReserve();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE, $createClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::noTrust();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NO_TRUST, $createClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::notAuthorized();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED, $createClaimableBalanceResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $createClaimableBalanceResultCode = CreateClaimableBalanceResultCode::underfunded();

        $this->assertInstanceOf(CreateClaimableBalanceResultCode::class, $createClaimableBalanceResultCode);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED, $createClaimableBalanceResultCode->getType());
    }
}
