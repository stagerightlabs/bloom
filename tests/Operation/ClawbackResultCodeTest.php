<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClawbackResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackResultCode
 */
class ClawbackResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => ClawbackResultCode::CLAWBACK_SUCCESS,
            -1 => ClawbackResultCode::CLAWBACK_MALFORMED,
            -2 => ClawbackResultCode::CLAWBACK_NOT_CLAWBACK_ENABLED,
            -3 => ClawbackResultCode::CLAWBACK_NO_TRUST,
            -4 => ClawbackResultCode::CLAWBACK_UNDERFUNDED,
        ];
        $clawbackResultCode = new ClawbackResultCode();

        $this->assertEquals($expected, $clawbackResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $clawbackResultCode = ClawbackResultCode::success();

        $this->assertInstanceOf(ClawbackResultCode::class, $clawbackResultCode);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_SUCCESS, $clawbackResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $clawbackResultCode = ClawbackResultCode::malformed();

        $this->assertInstanceOf(ClawbackResultCode::class, $clawbackResultCode);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_MALFORMED, $clawbackResultCode->getType());
    }

    /**
     * @test
     * @covers ::notClawbackEnabled
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_clawback_enabled_type()
    {
        $clawbackResultCode = ClawbackResultCode::notClawbackEnabled();

        $this->assertInstanceOf(ClawbackResultCode::class, $clawbackResultCode);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_NOT_CLAWBACK_ENABLED, $clawbackResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $clawbackResultCode = ClawbackResultCode::noTrust();

        $this->assertInstanceOf(ClawbackResultCode::class, $clawbackResultCode);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_NO_TRUST, $clawbackResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $clawbackResultCode = ClawbackResultCode::underfunded();

        $this->assertInstanceOf(ClawbackResultCode::class, $clawbackResultCode);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_UNDERFUNDED, $clawbackResultCode->getType());
    }
}
