<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use Brick\Math\BigDecimal;
use StageRightLabs\Bloom\Exception\MathException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\ScaledAmount
 */
class ScaledAmountTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_native_integer()
    {
        $scaledAmount = ScaledAmount::of(100);
        $this->assertInstanceOf(ScaledAmount::class, $scaledAmount);
        $this->assertTrue($scaledAmount->isEqualTo(ScaledAmount::of(100)));
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_an_int64()
    {
        $scaledAmount = ScaledAmount::of(Int64::of('12345678'));
        $this->assertInstanceOf(ScaledAmount::class, $scaledAmount);
        $this->assertEquals('1.2345678', $scaledAmount->toNativeString());
    }

    /**
     * @test
     * @covers ::of
     */
    public function invalid_values_will_be_rejected()
    {
        $this->expectException(MathException::class);
        ScaledAmount::of('922,337,203,685.4775808');
        // Max value is '922,337,203,685.4775807'
    }

    /**
     * @test
     * @covers ::of
     */
    public function commas_are_automatically_removed_from_string_values()
    {
        $scaled = ScaledAmount::of('922,337,203,685.4775807');
        $this->assertEquals('922337203685.4775807', strval($scaled));
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_catches_brick_math_exceptions()
    {
        $this->expectException(MathException::class);
        ScaledAmount::of('abc');
    }

    /**
     * @test
     * @covers ::isEqualTo
     */
    public function it_can_check_for_equivalence()
    {
        $this->assertTrue(ScaledAmount::of(100)->isEqualTo(ScaledAmount::of(100)));
        $this->assertFalse(ScaledAmount::of(100)->isEqualTo(ScaledAmount::of(256)));
    }

    /**
     * @test
     * @covers ::isEqualTo
     */
    public function it_can_check_for_equivalence_using_allowed_types()
    {
        $this->assertTrue(ScaledAmount::of(100)->isEqualTo(BigDecimal::of(100)));
        $this->assertTrue(ScaledAmount::of(100)->isEqualTo(100));
        $this->assertTrue(ScaledAmount::of(100)->isEqualTo(100.00));
        $this->assertTrue(ScaledAmount::of(100)->isEqualTo('100'));
    }

    /**
     * @test
     * @covers ::descale
     */
    public function it_can_be_converted_to_an_int64()
    {
        $scaledA = ScaledAmount::of('256');
        $int64A = $scaledA->descale();
        $scaledB = ScaledAmount::of('922,337,203,685.4775807');
        $int64B = $scaledB->descale();

        $this->assertInstanceOf(Int64::class, $int64A);
        $this->assertEquals('256.0000000', strval($scaledA));
        $this->assertEquals('2560000000', strval($int64A));
        $this->assertEquals('922337203685.4775807', strval($scaledB));
        $this->assertEquals('9223372036854775807', strval($int64B));
    }

    /**
     * @test
     * @covers ::toNativeString
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $scaled = ScaledAmount::of('256');

        $this->assertEquals('256.0000000', strval($scaled));
        $this->assertEquals('256.0000000', $scaled->toNativeString());
    }

    /**
     * @test
     * @covers ::toBigDecimal
     */
    public function it_can_be_converted_to_a_big_decimal()
    {
        $scaled = ScaledAmount::of('256');
        $this->assertInstanceOf(BigDecimal::class, $scaled->toBigDecimal());
    }
}
