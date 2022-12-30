<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\SetTrustLineFlagsResult;
use StageRightLabs\Bloom\Operation\SetTrustLineFlagsResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetTrustLineFlagsResult
 */
class SetTrustLineFlagsResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(SetTrustLineFlagsResultCode::class, SetTrustLineFlagsResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS       => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_MALFORMED     => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_CANT_REVOKE   => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_INVALID_STATE => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE   => XDR::VOID,
        ];

        $this->assertEquals($expected, SetTrustLineFlagsResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_success_union()
    {
        $setTrustLineFlags = SetTrustLineFlagsResult::success();
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS, $setTrustLineFlags->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_when_no_value_is_set()
    {
        $this->assertNull((new SetTrustLineFlagsResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $setTrustLineFlagsResult = SetTrustLineFlagsResult::simulate(SetTrustLineFlagsResultCode::lowReserve());

        $this->assertInstanceOf(SetTrustLineFlagsResult::class, $setTrustLineFlagsResult);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE, $setTrustLineFlagsResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $setTrustLineFlagsResultA = SetTrustLineFlagsResult::success();
        $setTrustLineFlagsResultB = new SetTrustLineFlagsResult();

        $this->assertTrue($setTrustLineFlagsResultA->wasSuccessful());
        $this->assertFalse($setTrustLineFlagsResultA->wasNotSuccessful());
        $this->assertTrue($setTrustLineFlagsResultB->wasNotSuccessful());
        $this->assertFalse($setTrustLineFlagsResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $setTrustLineFlagsResult = SetTrustLineFlagsResult::simulate(SetTrustLineFlagsResultCode::lowReserve());

        $this->assertNotEmpty($setTrustLineFlagsResult->getErrorMessage());
        $this->assertEquals('set_trust_line_flags_low_reserve', $setTrustLineFlagsResult->getErrorCode());
        $this->assertNull((new SetTrustLineFlagsResult())->getErrorMessage());
        $this->assertNull((new SetTrustLineFlagsResult())->getErrorCode());
    }
}
