<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Value
 */
class ValueTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_helper()
    {
        $value = Value::of('example');

        $this->assertInstanceOf(Value::class, $value);
        $this->assertEquals('example', $value->getValue());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $value = Value::of('example');
        $buffer = XDR::fresh()->write($value);

        $this->assertEquals('AAAAB2V4YW1wbGUA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $value = XDR::fromBase64('AAAAB2V4YW1wbGUA')->read(Value::class);

        $this->assertInstanceOf(Value::class, $value);
        $this->assertEquals('example', $value->getValue());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $value = Value::of('example');

        $this->assertEquals('example', strval($value));
        $this->assertEquals('example', $value->toNativeString());
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::getValue
     */
    public function it_accepts_a_value()
    {
        $value = (new Value())->withValue('example');

        $this->assertInstanceOf(Value::class, $value);
        $this->assertEquals('example', $value->getValue());
    }
}
