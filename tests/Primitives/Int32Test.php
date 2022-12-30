<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Int32
 */
class Int32Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     * @covers ::withValue
     */
    public function it_can_be_instantiated_from_a_native_integer()
    {
        $integer = Int32::of(256);

        $this->assertInstanceOf(Int32::class, $integer);
    }

    /**
     * @test
     * @covers ::of
     * @covers ::withValue
     */
    public function it_can_be_instantiated_from_another_int32()
    {
        $integerA = Int32::of(256);
        $integerB = Int32::of($integerA);

        $this->assertInstanceOf(Int32::class, $integerB);
        $this->assertNotEquals(spl_object_id($integerA), spl_object_id($integerB));
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_native_integers_larger_than_the_maximum_allowed_value()
    {
        $this->expectException(InvalidArgumentException::class);
        Int32::of(2147483648);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_numbers_less_the_minimum_allowed_value()
    {
        $this->expectException(InvalidArgumentException::class);
        Int32::of(-2147483648);
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_a_native_integer_representation()
    {
        $integer = Int32::of(256);

        $this->assertEquals(256, $integer->toNativeInt());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $integer = Int32::of(256);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAABAA==', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $integer = XDR::fromBase64('AAABAA==')->read(Int32::class);

        $this->assertInstanceOf(Int32::class, $integer);
        $this->assertEquals(256, $integer->toNativeInt());
    }
}
