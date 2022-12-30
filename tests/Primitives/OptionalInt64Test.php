<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\OptionalInt64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\OptionalInt64
 */
class OptionalInt64Test extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(Int64::class, OptionalInt64::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_int64()
    {
        $optional = OptionalInt64::some(Int64::of(1));
        $this->assertInstanceOf(OptionalInt64::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_int64()
    {
        $optional = OptionalInt64::some(Int64::of(1));
        $this->assertInstanceOf(Int64::class, $optional->unwrap());
    }
}
