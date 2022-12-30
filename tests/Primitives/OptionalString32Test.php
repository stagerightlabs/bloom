<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\OptionalString32;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\OptionalString32
 */
class OptionalString32Test extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(String32::class, OptionalString32::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_string_32()
    {
        $optional = OptionalString32::some(String32::of('example'));

        $this->assertInstanceOf(OptionalString32::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_native_string()
    {
        $optional = OptionalString32::some('example');

        $this->assertInstanceOf(OptionalString32::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_string_32()
    {
        $optional = OptionalString32::some(String32::of('example'));
        $this->assertInstanceOf(String32::class, $optional->unwrap());
    }
}
