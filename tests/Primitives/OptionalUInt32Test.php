<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\OptionalUInt32;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\OptionalUInt32
 */
class OptionalUInt32Test extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(UInt32::class, OptionalUInt32::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_uint_32()
    {
        $optional = OptionalUInt32::some(UInt32::of(1));

        $this->assertInstanceOf(OptionalUInt32::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_native_int()
    {
        $optional = OptionalUInt32::some(1);

        $this->assertInstanceOf(OptionalUInt32::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_uint_32()
    {
        $optional = OptionalUInt32::some(UInt32::of(1));
        $this->assertInstanceOf(UInt32::class, $optional->unwrap());
    }
}
