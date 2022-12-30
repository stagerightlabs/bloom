<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Str
 */
class StrTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $str = String32::of('Hello World');

        $this->assertInstanceOf(String32::class, $str);
        $this->assertEquals('Hello World', $str->getValue());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $str = String32::of('Hello World');
        $buffer = XDR::fresh()->write($str)->toBase64();

        $this->assertEquals('AAAAC0hlbGxvIFdvcmxkAA==', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $str = XDR::fromBase64('AAAAC0hlbGxvIFdvcmxkAA==')->read(String32::class);

        $this->assertInstanceOf(String32::class, $str);
        $this->assertEquals('Hello World', $str->getValue());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $str = String32::of('Hello World');
        $this->assertEquals('Hello World', (string)$str);

        $str = String32::of('Hello World');
        $this->assertEquals('Hello World', $str->toNativeString());
    }

    /**
     * @test
     * @covers ::getValue
     */
    public function it_returns_its_underlying_value()
    {
        $str = String32::of('Hello World');

        $this->assertEquals('Hello World', $str->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function its_value_can_be_set()
    {
        $str = (new String32())->withValue('Hello World');

        $this->assertEquals('Hello World', $str->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_rejects_values_that_exceed_the_maximum_length()
    {
        $this->expectException(InvalidArgumentException::class);
        String32::of('aaaaabbbbbcccccdddddeeeeefffffggh');
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_is_immutable()
    {
        $strA = String32::of('foo');
        $strB = $strA->withValue('bar');

        $this->assertEquals('foo', $strA->getValue());
        $this->assertEquals('bar', $strB->getValue());
    }
}
