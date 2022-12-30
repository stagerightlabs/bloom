<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\ClawbackResult;
use StageRightLabs\Bloom\Operation\ClawbackResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackResult
 */
class ClawbackResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClawbackResultCode::class, ClawbackResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClawbackResultCode::CLAWBACK_SUCCESS              => XDR::VOID,
            ClawbackResultCode::CLAWBACK_MALFORMED            => XDR::VOID,
            ClawbackResultCode::CLAWBACK_NOT_CLAWBACK_ENABLED => XDR::VOID,
            ClawbackResultCode::CLAWBACK_NO_TRUST             => XDR::VOID,
            ClawbackResultCode::CLAWBACK_UNDERFUNDED          => XDR::VOID,
        ];

        $this->assertEquals($expected, ClawbackResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_empty_union()
    {
        $clawbackResult = ClawbackResult::success();
        $this->assertEquals(ClawbackResultCode::CLAWBACK_SUCCESS, $clawbackResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new ClawbackResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $clawbackResult = ClawbackResult::simulate(ClawbackResultCode::malformed());

        $this->assertInstanceOf(ClawbackResult::class, $clawbackResult);
        $this->assertEquals(ClawbackResultCode::CLAWBACK_MALFORMED, $clawbackResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $clawbackResultA = ClawbackResult::success();
        $clawbackResultB = new ClawbackResult();

        $this->assertTrue($clawbackResultA->wasSuccessful());
        $this->assertFalse($clawbackResultA->wasNotSuccessful());
        $this->assertTrue($clawbackResultB->wasNotSuccessful());
        $this->assertFalse($clawbackResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $clawbackResult = ClawbackResult::simulate(ClawbackResultCode::malformed());

        $this->assertNotEmpty($clawbackResult->getErrorMessage());
        $this->assertEquals('clawback_malformed', $clawbackResult->getErrorCode());
        $this->assertNull((new ClawbackResult())->getErrorMessage());
        $this->assertNull((new ClawbackResult())->getErrorCode());
    }
}
