<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\AllowTrustResult;
use StageRightLabs\Bloom\Operation\AllowTrustResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\AllowTrustResult
 */
class AllowTrustResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AllowTrustResultCode::class, AllowTrustResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            AllowTrustResultCode::ALLOW_TRUST_SUCCESS            => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_MALFORMED          => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_NO_TRUST_LINE      => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_TRUST_NOT_REQUIRED => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_CANT_REVOKE        => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_SELF_NOT_ALLOWED   => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE        => XDR::VOID,
        ];

        $this->assertEquals($expected, AllowTrustResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $allowTrustResult = AllowTrustResult::success();
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_SUCCESS, $allowTrustResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new AllowTrustResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $allowTrustResult = AllowTrustResult::simulate(AllowTrustResultCode::lowReserve());

        $this->assertInstanceOf(AllowTrustResult::class, $allowTrustResult);
        $this->assertEquals(AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE, $allowTrustResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $allowTrustResultA = AllowTrustResult::success();
        $allowTrustResultB = new AllowTrustResult();

        $this->assertTrue($allowTrustResultA->wasSuccessful());
        $this->assertFalse($allowTrustResultA->wasNotSuccessful());
        $this->assertTrue($allowTrustResultB->wasNotSuccessful());
        $this->assertFalse($allowTrustResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $allowTrustResult = AllowTrustResult::simulate(AllowTrustResultCode::lowReserve());

        $this->assertNotEmpty($allowTrustResult->getErrorMessage());
        $this->assertNull((new AllowTrustResult())->getErrorMessage());
        $this->assertEquals('allow_trust_low_reserve', $allowTrustResult->getErrorCode());
        $this->assertNull((new AllowTrustResult())->getErrorCode());
    }
}
