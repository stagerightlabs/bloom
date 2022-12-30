<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimePoint;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TimePoint
 */
class TimePointTest extends TestCase
{
    /**
     * @test
     * @covers ::now
     */
    public function it_can_be_created_with_the_current_time()
    {
        $timePoint = TimePoint::now();
        $this->assertInstanceOf(TimePoint::class, $timePoint);
    }

    /**
     * @test
     * @covers ::fromDateTime
     */
    public function it_can_be_created_from_a_datetimes()
    {
        $datetime = new DateTime('2021-01-01');
        $timePoint = TimePoint::fromDateTime($datetime);

        $this->assertInstanceOf(TimePoint::class, $timePoint);
        $this->assertEquals(1609459200, $timePoint->toNativeInt());
    }

    /**
     * @test
     * @covers ::fromUnixEpoch
     * @covers ::toUnixEpoch
     */
    public function it_can_be_created_from_a_unix_epoch_integer()
    {
        $timePoint = TimePoint::fromUnixEpoch(1609459200);

        $this->assertInstanceOf(TimePoint::class, $timePoint);
        $this->assertEquals(1609459200, $timePoint->toNativeInt());
        $this->assertEquals(1609459200, $timePoint->toUnixEpoch());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_can_be_created_from_strings()
    {
        $timePoint = TimePoint::fromNativeString('2021-01-01');

        $this->assertInstanceOf(TimePoint::class, $timePoint);
        $this->assertEquals(1609459200, $timePoint->toNativeInt());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_cannot_be_created_from_invalid_date_strings()
    {
        $this->expectException(InvalidArgumentException::class);
        TimePoint::fromNativeString('invalid-date-string');
    }

    /**
     * @test
     * @covers ::toDateTime
     */
    public function it_can_be_converted_to_datetimes()
    {
        $timePoint = TimePoint::of(1609459200);
        $dateTime = $timePoint->toDateTime();

        $this->assertInstanceOf(DateTime::class, $dateTime);
        $this->assertEquals('2021-01-01', $dateTime->format('Y-m-d'));
    }
}
