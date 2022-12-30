<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceResult;
use StageRightLabs\Bloom\Operation\CreateClaimableBalanceResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\CreateClaimableBalanceResult
 */
class CreateClaimableBalanceResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(CreateClaimableBalanceResultCode::class, CreateClaimableBalanceResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS        => ClaimableBalanceId::class,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED      => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE    => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NO_TRUST       => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED    => XDR::VOID,
        ];

        $this->assertEquals($expected, CreateClaimableBalanceResult::arms());
    }

    /**
     * @test
     * @covers ::wrapClaimableBalanceId
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_claimable_balance_id()
    {
        $createClaimableBalanceResult = CreateClaimableBalanceResult::wrapClaimableBalanceId(new ClaimableBalanceId());

        $this->assertInstanceOf(CreateClaimableBalanceResult::class, $createClaimableBalanceResult);
        $this->assertInstanceOf(ClaimableBalanceId::class, $createClaimableBalanceResult->unwrap());
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS, $createClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new CreateClaimableBalanceResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $createClaimableBalanceResult = CreateClaimableBalanceResult::simulate(CreateClaimableBalanceResultCode::malformed());

        $this->assertInstanceOf(CreateClaimableBalanceResult::class, $createClaimableBalanceResult);
        $this->assertEquals(CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED, $createClaimableBalanceResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $createClaimableBalanceResultA = CreateClaimableBalanceResult::wrapClaimableBalanceId(new ClaimableBalanceId());
        $createClaimableBalanceResultB = new CreateClaimableBalanceResult();

        $this->assertTrue($createClaimableBalanceResultA->wasSuccessful());
        $this->assertFalse($createClaimableBalanceResultA->wasNotSuccessful());
        $this->assertTrue($createClaimableBalanceResultB->wasNotSuccessful());
        $this->assertFalse($createClaimableBalanceResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $createClaimableBalanceResult = CreateClaimableBalanceResult::simulate(CreateClaimableBalanceResultCode::malformed());

        $this->assertNotEmpty($createClaimableBalanceResult->getErrorMessage());
        $this->assertEquals('create_claimable_balance_malformed', $createClaimableBalanceResult->getErrorCode());
        $this->assertNull((new CreateClaimableBalanceResult())->getErrorMessage());
        $this->assertNull((new CreateClaimableBalanceResult())->getErrorCode());
    }
}
