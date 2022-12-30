<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\SetTrustLineFlagsResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetTrustLineFlagsResultCode
 */
class SetTrustLineFlagsResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS,
            -1 => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_MALFORMED,
            -2 => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE,
            -3 => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_CANT_REVOKE,
            -4 => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_INVALID_STATE,
            -5 => SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE,
        ];
        $setTrustLineFlagsResultCode = new SetTrustLineFlagsResultCode();

        $this->assertEquals($expected, $setTrustLineFlagsResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::success();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS, $setTrustLineFlagsResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::malformed();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_MALFORMED, $setTrustLineFlagsResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrustLine
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_line_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::noTrustLine();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE, $setTrustLineFlagsResultCode->getType());
    }

    /**
     * @test
     * @covers ::cantRevoke
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cant_revoke_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::cantRevoke();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_CANT_REVOKE, $setTrustLineFlagsResultCode->getType());
    }

    /**
     * @test
     * @covers ::invalidState
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_invalid_state_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::invalidState();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_INVALID_STATE, $setTrustLineFlagsResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $setTrustLineFlagsResultCode = SetTrustLineFlagsResultCode::lowReserve();

        $this->assertInstanceOf(SetTrustLineFlagsResultCode::class, $setTrustLineFlagsResultCode);
        $this->assertEquals(SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE, $setTrustLineFlagsResultCode->getType());
    }
}
