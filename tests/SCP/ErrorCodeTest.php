<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\ErrorCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ErrorCode
 */
class ErrorCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ErrorCode::ERR_MISC, // unspecific error
            1 => ErrorCode::ERR_DATA, // malformed data
            2 => ErrorCode::ERR_CONF, // misconfiguration error
            3 => ErrorCode::ERR_AUTH, // authentication failure
            4 => ErrorCode::ERR_LOAD, // system overloaded
        ];
        $errorCode = new ErrorCode();

        $this->assertEquals($expected, $errorCode->getOptions());
    }

    /**
     * @test
     * @covers ::misc
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_misc_type()
    {
        $errorCode = ErrorCode::misc();

        $this->assertInstanceOf(ErrorCode::class, $errorCode);
        $this->assertEquals(ErrorCode::ERR_MISC, $errorCode->getType());
    }

    /**
     * @test
     * @covers ::data
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_data_type()
    {
        $errorCode = ErrorCode::data();

        $this->assertInstanceOf(ErrorCode::class, $errorCode);
        $this->assertEquals(ErrorCode::ERR_DATA, $errorCode->getType());
    }

    /**
     * @test
     * @covers ::conf
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_conf_type()
    {
        $errorCode = ErrorCode::conf();

        $this->assertInstanceOf(ErrorCode::class, $errorCode);
        $this->assertEquals(ErrorCode::ERR_CONF, $errorCode->getType());
    }

    /**
     * @test
     * @covers ::auth
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_auth_type()
    {
        $errorCode = ErrorCode::auth();

        $this->assertInstanceOf(ErrorCode::class, $errorCode);
        $this->assertEquals(ErrorCode::ERR_AUTH, $errorCode->getType());
    }

    /**
     * @test
     * @covers ::load
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_load_type()
    {
        $errorCode = ErrorCode::load();

        $this->assertInstanceOf(ErrorCode::class, $errorCode);
        $this->assertEquals(ErrorCode::ERR_LOAD, $errorCode->getType());
    }
}
