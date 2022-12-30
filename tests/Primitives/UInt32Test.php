<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\UInt32
 */
class UInt32Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     * @covers ::withNativeInt
     */
    public function it_can_be_instantiated_from_a_native_integer()
    {
        $integer = UInt32::of(256);

        $this->assertInstanceOf(UInt32::class, $integer);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_native_integers_larger_than_maximum_allowed_value()
    {
        $this->expectException(InvalidArgumentException::class);
        UInt32::of(4294967296);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_numbers_less_than_zero()
    {
        $this->expectException(InvalidArgumentException::class);
        UInt32::of(-1);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_uint32()
    {
        $integerA = UInt32::of(256);
        $integerB = UInt32::of($integerA);

        $this->assertInstanceOf(UInt32::class, $integerB);
        $this->assertNotEquals(spl_object_id($integerA), spl_object_id($integerB));
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $integer = UInt32::of(256);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAABAA==', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $integer = XDR::fromBase64('AAABAA==')->read(UInt32::class);

        $this->assertInstanceOf(UInt32::class, $integer);
        $this->assertEquals(256, $integer->toNativeInt());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_can_be_converted_to_a_native_int()
    {
        $integer = UInt32::of(256);

        $this->assertEquals(256, $integer->toNativeInt());
    }

    /**
     * @test
     * @covers ::isEqualTo
     */
    public function it_can_compare_itself_to_other_uint32_values()
    {
        $integer = UInt32::of(256);

        $this->assertTrue($integer->isEqualTo(UInt32::of(256)));
        $this->assertFalse($integer->isEqualTo(UInt32::of(255)));
    }

    /**
     * @test
     * @covers ::isEqualTo
     */
    public function it_can_compare_itself_to_other_int_values()
    {
        $integer = UInt32::of(256);

        $this->assertTrue($integer->isEqualTo(256));
        $this->assertFalse($integer->isEqualTo(255));
    }
}
