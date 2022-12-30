<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\SetOptionsResult;
use StageRightLabs\Bloom\Operation\SetOptionsResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetOptionsResult
 */
class SetOptionsResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(SetOptionsResultCode::class, SetOptionsResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            SetOptionsResultCode::SET_OPTIONS_SUCCESS                 => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE             => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_TOO_MANY_SIGNERS        => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_BAD_FLAGS               => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_INVALID_INFLATION       => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_CANT_CHANGE             => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_UNKNOWN_FLAG            => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE  => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_BAD_SIGNER              => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_INVALID_HOME_DOMAIN     => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED => XDR::VOID,
        ];

        $this->assertEquals($expected, SetOptionsResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $setOptionsResult = SetOptionsResult::success();
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_SUCCESS, $setOptionsResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_when_no_value_is_set()
    {
        $this->assertNull((new SetOptionsResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $setOptionsResult = SetOptionsResult::simulate(SetOptionsResultCode::lowReserve());

        $this->assertInstanceOf(SetOptionsResult::class, $setOptionsResult);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE, $setOptionsResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_is_successful()
    {
        $setOptionsResultA = SetOptionsResult::success();
        $setOptionsResultB = new SetOptionsResult();

        $this->assertTrue($setOptionsResultA->wasSuccessful());
        $this->assertFalse($setOptionsResultA->wasNotSuccessful());
        $this->assertTrue($setOptionsResultB->wasNotSuccessful());
        $this->assertFalse($setOptionsResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $setOptionsResult = SetOptionsResult::simulate(SetOptionsResultCode::lowReserve());

        $this->assertNotEmpty($setOptionsResult->getErrorMessage());
        $this->assertEquals('set_options_low_reserve', $setOptionsResult->getErrorCode());
        $this->assertNull((new SetOptionsResult())->getErrorMessage());
        $this->assertNull((new SetOptionsResult())->getErrorCode());
    }
}
