<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\OptionalTimeBounds;
use StageRightLabs\Bloom\Transaction\TimeBounds;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\OptionalTimeBounds
 */
class OptionalTimeBoundsTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(TimeBounds::class, OptionalTimeBounds::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_time_bounds_object()
    {
        $timeBounds = TimeBounds::oneYear();
        $optional = OptionalTimeBounds::some($timeBounds);

        $this->assertInstanceOf(OptionalTimeBounds::class, $optional);
        $this->assertInstanceOf(TimeBounds::class, $optional->unwrap());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_underlying_time_bounds_or_null()
    {
        $timeBoundsA = OptionalTimeBounds::none();
        $timeBoundsB = OptionalTimeBounds::some(TimeBounds::oneYear());

        $this->assertNull($timeBoundsA->unwrap());
        $this->assertInstanceOf(TimeBounds::class, $timeBoundsB->unwrap());
    }
}
