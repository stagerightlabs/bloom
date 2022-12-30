<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\MathException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Integer
 */
class IntegerTest extends TestCase
{
    /**
     * @test
     * @covers ::of()
     */
    public function it_instantiates_new_instances()
    {
        $firstInteger = UInt64::of('1');
        $this->assertInstanceOf(UInt64::class, $firstInteger);
        $this->assertTrue($firstInteger->isEqualTo(1));

        $secondInteger = UInt64::of($firstInteger);
        $this->assertInstanceOf(UInt64::class, $secondInteger);
        $this->assertTrue($secondInteger->isEqualTo(1));
    }

    /**
     * @test
     * @covers ::of()
     */
    public function it_catches_brick_math_exceptions()
    {
        $this->expectException(MathException::class);
        UInt64::of('abcd');
    }

    /**
     * @test
     * @covers ::fromBytes
     */
    public function it_instantiates_new_instances_from_bytes()
    {
        $integer = UInt64::fromBytes('a');

        $this->assertInstanceOf(UInt64::class, $integer);
        $this->assertTrue($integer->isEqualTo(97));
    }

    /**
     * @test
     * @covers ::toBytes
     */
    public function it_returns_a_byte_representation()
    {
        $bytes = UInt64::of(97)->toBytes();

        $this->assertEquals('a', $bytes);
    }

    /**
     * @test
     * @covers ::getBitLength
     */
    public function it_returns_a_bit_length()
    {
        $length = UInt64::of(255)->getBitLength();
        $this->assertEquals(8, $length);

        $length = UInt64::of(256)->getBitLength();
        $this->assertEquals(9, $length);
    }

    /**
     * @test
     * @covers ::isEqualTo
     */
    public function it_performs_equality_checks()
    {
        $integer = UInt64::of(255);
        $alternate = UInt64::of(256);

        $this->assertTrue($integer->isEqualTo(255));
        $this->assertFalse($integer->isEqualTo(256));
        $this->assertFalse($integer->isEqualTo($alternate));
    }

    /**
     * @test
     * @covers ::plus
     */
    public function it_performs_addition()
    {
        $integer = UInt64::of(255)->plus(1);
        $this->assertTrue($integer->isEqualTo(256));
        $this->assertFalse($integer->isEqualTo(255));

        $alternate = UInt64::of(256);
        $integer = $integer->plus($alternate);
        $this->assertTrue($integer->isEqualTo(512));
        $this->assertFalse($integer->isEqualTo(256));
    }

    /**
     * @test
     * @covers ::minus
     */
    public function it_performs_subtraction()
    {
        $integer = UInt64::of(255)->minus(1);

        $this->assertTrue($integer->isEqualTo(254));
        $this->assertFalse($integer->isEqualTo(255));

        $alternate = UInt64::of(128);
        $integer = $integer->minus($alternate);

        $this->assertTrue($integer->isEqualTo(126));
        $this->assertFalse($integer->isEqualTo(254));
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_converts_qualified_values_to_native_integers()
    {
        $qualified = UInt64::of(255);
        $this->assertIsInt($qualified->toNativeInt());

        $this->expectException(\Brick\Math\Exception\MathException::class);
        UInt64::of('9223372036854775808')->toNativeInt();
    }

    /**
     * @test
     * @covers ::isGreaterThan
     */
    public function it_performs_greater_than_comparisons()
    {
        $integer = UInt64::of(255);
        $alternate = UInt64::of(512);

        $this->assertTrue($integer->isGreaterThan(128));
        $this->assertFalse($integer->isGreaterThan($alternate));
    }

    /**
     * @test
     * @covers ::isNegative
     */
    public function it_performs_negative_value_assertions()
    {
        $integer = UInt64::of(255);
        $this->assertFalse($integer->isNegative());

        $integer = UInt64::of(-255);
        $this->assertTrue($integer->isNegative());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $string = UInt64::of(255)->toNativeString();

        $this->assertIsString($string);
        $this->assertEquals('255', $string);
    }

    /**
     * @test
     * @covers ::toBigInteger
     */
    public function it_returns_a_brick_math_big_integer_representation()
    {
        $integer = UInt64::of(255)->toBigInteger();

        $this->assertInstanceOf(\Brick\Math\BigInteger::class, $integer);
    }
}
