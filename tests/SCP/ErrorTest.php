<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\SCP\Error;
use StageRightLabs\Bloom\SCP\ErrorCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\Error
 */
class ErrorTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $error = (new Error())->withCode(ErrorCode::conf());
        $buffer = XDR::fresh()->write($error);

        $this->assertEquals('AAAAAgAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_error_code_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new Error());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $error = XDR::fromBase64('AAAAAgAAAAA=')->read(Error::class);

        $this->assertInstanceOf(Error::class, $error);
        $this->assertInstanceOf(ErrorCode::class, $error->getCode());
        $this->assertEquals(ErrorCode::ERR_CONF, $error->getCode()->getType());
        $this->assertInstanceOf(String100::class, $error->getMessage());
        $this->assertEquals('', $error->getMessage()->toNativeString());
    }

    /**
     * @test
     * @covers ::withCode
     * @covers ::getCode
     */
    public function it_accepts_an_error_code()
    {
        $error = (new Error())->withCode(ErrorCode::conf());

        $this->assertInstanceOf(Error::class, $error);
        $this->assertInstanceOf(ErrorCode::class, $error->getCode());
    }

    /**
     * @test
     * @covers ::withMessage
     * @covers ::getMessage
     */
    public function it_accepts_a_string100_as_a_message()
    {
        $error = (new Error())->withMessage(String100::of('hello'));

        $this->assertInstanceOf(Error::class, $error);
        $this->assertInstanceOf(String100::class, $error->getMessage());
    }

    /**
     * @test
     * @covers ::withMessage
     * @covers ::getMessage
     */
    public function it_accepts_a_native_string_as_a_message()
    {
        $error = (new Error())->withMessage('hello');

        $this->assertInstanceOf(Error::class, $error);
        $this->assertInstanceOf(String100::class, $error->getMessage());
    }
}
