<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\SetOptionsResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\SetOptionsResultCode
 */
class SetOptionsResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0   => SetOptionsResultCode::SET_OPTIONS_SUCCESS,
            -1  => SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE,
            -2  => SetOptionsResultCode::SET_OPTIONS_TOO_MANY_SIGNERS,
            -3  => SetOptionsResultCode::SET_OPTIONS_BAD_FLAGS,
            -4  => SetOptionsResultCode::SET_OPTIONS_INVALID_INFLATION,
            -5  => SetOptionsResultCode::SET_OPTIONS_CANT_CHANGE,
            -6  => SetOptionsResultCode::SET_OPTIONS_UNKNOWN_FLAG,
            -7  => SetOptionsResultCode::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE,
            -8  => SetOptionsResultCode::SET_OPTIONS_BAD_SIGNER,
            -9  => SetOptionsResultCode::SET_OPTIONS_INVALID_HOME_DOMAIN,
            -10 => SetOptionsResultCode::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED,
        ];
        $setOptionsResultCode = new SetOptionsResultCode();

        $this->assertEquals($expected, $setOptionsResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::success();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_SUCCESS, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::lowReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_low_reserve_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::lowReserve();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::tooManySigners
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_too_many_signers_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::tooManySigners();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_TOO_MANY_SIGNERS, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::badFlags
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_bad_flags_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::badFlags();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_BAD_FLAGS, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::invalidInflation
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_invalid_inflation_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::invalidInflation();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_INVALID_INFLATION, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::cantChange
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_cant_change_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::cantChange();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_CANT_CHANGE, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::unknownFlag
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_unknown_flag_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::unknownFlag();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_UNKNOWN_FLAG, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::thresholdOutOfRange
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_threshold_out_of_range_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::thresholdOutOfRange();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::badSigner
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_bad_signer_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::badSigner();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_BAD_SIGNER, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::invalidHomeDomain
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_invalid_home_domain_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::invalidHomeDomain();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_INVALID_HOME_DOMAIN, $setOptionsResultCode->getType());
    }

    /**
     * @test
     * @covers ::authRevocableRequired
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_auth_revocable_required_type()
    {
        $setOptionsResultCode = SetOptionsResultCode::authRevocableRequired();

        $this->assertInstanceOf(SetOptionsResultCode::class, $setOptionsResultCode);
        $this->assertEquals(SetOptionsResultCode::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED, $setOptionsResultCode->getType());
    }
}
