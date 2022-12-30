<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Number;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Int64
 */
class Int64Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $integer = Int64::of(256);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAAAAAAAAQA=', $buffer);

        $integer = Int64::of(-256);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('/////////wA=', $buffer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr_when_the_value_is_zero()
    {
        $integer = Int64::of(0);
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAAAAAAAAAA=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $integer = XDR::fromBase64('AAAAAAAAAQA=')->read(Int64::class);

        $this->assertInstanceOf(Int64::class, $integer);
        $this->assertTrue($integer->isEqualTo(256));
    }

    /**
     * @test
     * @covers ::scale
     */
    public function it_can_be_converted_to_a_scaled_amount()
    {
        $scaledAmount = Int64::of(256)->scale();

        $this->assertInstanceOf(ScaledAmount::class, $scaledAmount);
        $this->assertEquals('0.0000256', $scaledAmount->toNativeString());
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_a_native_int()
    {
        $int64 = Int64::normalize(1);

        $this->assertInstanceOf(Int64::class, $int64);
        $this->assertEquals(1, $int64->toNativeInt());
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_a_string()
    {
        $int64 = Int64::normalize('1');

        $this->assertInstanceOf(Int64::class, $int64);
        $this->assertEquals(10000000, $int64->toNativeInt());
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_a_scaled_amount()
    {
        $int64 = Int64::normalize(ScaledAmount::of('1'));

        $this->assertInstanceOf(Int64::class, $int64);
        $this->assertEquals(10000000, $int64->toNativeInt());
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function normalizing_an_int64_returns_a_clone()
    {
        $int64a = Int64::of(1);
        $int64b = Int64::normalize($int64a);

        $this->assertNotEquals(spl_object_id($int64a), spl_object_id($int64b));
    }

    /**
     * @test
     * @covers ::max
     */
    public function it_can_return_the_maximum_allowed_int64_value()
    {
        $this->assertTrue(Int64::max()->isEqualTo(Int64::of(Number::MAX_INT64)));
    }
}
