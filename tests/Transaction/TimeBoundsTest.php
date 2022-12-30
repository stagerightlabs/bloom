<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TimeBounds;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TimeBounds
 */
class TimeBoundsTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $timeBounds = TimeBounds::between('2021-01-01', '2021-01-05');
        $buffer = XDR::fresh()->write($timeBounds)->toBase64();

        $this->assertEquals('AAAAAF/uZgAAAAAAX/OsAA==', $buffer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_min_time_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $timeBounds = (new TimeBounds())->withMaxTime('2021-01-05');
        XDR::fresh()->write($timeBounds);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_max_time_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $timeBounds = (new TimeBounds())->withMinTime('2021-01-01');
        XDR::fresh()->write($timeBounds);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $timeBounds = XDR::fromBase64('AAAAAF/uZgAAAAAAX/OsAA==')
            ->read(TimeBounds::class);

        $this->assertInstanceOf(TimeBounds::class, $timeBounds);
        $this->assertEquals(1609459200, $timeBounds->getMinTime()->toNativeInt());
        $this->assertEquals(1609804800, $timeBounds->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::between
     */
    public function timebounds_can_be_created_from_two_dates()
    {
        $timeBounds = TimeBounds::between('2021-01-01', '2021-01-05');

        $this->assertInstanceOf(TimeBounds::class, $timeBounds);
        $this->assertEquals(1609459200, $timeBounds->getMinTime()->toNativeInt());
        $this->assertEquals(1609804800, $timeBounds->getMaxTime()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMinTime
     */
    public function it_returns_the_min_time_point()
    {
        $timePoint = TimePoint::fromNativeString('2022-01-01');
        $timeBounds = (new TimeBounds())->withMinTime($timePoint);

        $this->assertTrue($timePoint->isEqualTo($timeBounds->getMinTime()->toNativeInt()));
    }

    /**
     * @test
     * @covers ::withMinTime
     */
    public function it_accepts_a_string_as_a_min_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMinTime('2022-01-01');
        $secondTimeBounds = $firstTimeBounds->withMinTime('2000-01-01');

        $this->assertNotEquals(
            $firstTimeBounds->getMinTime()->toNativeInt(),
            $secondTimeBounds->getMinTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMinTime
     */
    public function it_accepts_a_datetime_as_a_min_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMinTime(new DateTime('2022-01-01'));
        $secondTimeBounds = $firstTimeBounds->withMinTime(new DateTime('2000-01-01'));

        $this->assertNotEquals(
            $firstTimeBounds->getMinTime()->toNativeInt(),
            $secondTimeBounds->getMinTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMinTime
     */
    public function it_accepts_an_int_as_a_min_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMinTime(1640995200);
        $secondTimeBounds = $firstTimeBounds->withMinTime(1643673600);

        $this->assertNotEquals(
            $firstTimeBounds->getMinTime()->toNativeInt(),
            $secondTimeBounds->getMinTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMinTime
     */
    public function it_accepts_a_time_point_as_a_min_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMinTime(TimePoint::fromNativeString('2022-01-01'));
        $secondTimeBounds = $firstTimeBounds->withMinTime(TimePoint::fromNativeString('2000-01-01'));

        $this->assertNotEquals(
            $firstTimeBounds->getMinTime()->toNativeInt(),
            $secondTimeBounds->getMinTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::getMaxTime
     */
    public function it_returns_the_max_time_point()
    {
        $timePoint = TimePoint::fromNativeString('2022-01-01');
        $timeBounds = (new TimeBounds())->withMaxTime($timePoint);

        $this->assertTrue($timePoint->isEqualTo($timeBounds->getMaxTime()->toNativeInt()));
    }

    /**
     * @test
     * @covers ::withMaxTime
     */
    public function it_accepts_a_string_as_a_max_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMaxTime('2022-01-01');
        $secondTimeBounds = $firstTimeBounds->withMaxTime('2000-01-01');

        $this->assertNotEquals(
            $firstTimeBounds->getMaxTime()->toNativeInt(),
            $secondTimeBounds->getMaxTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMaxTime
     */
    public function it_accepts_a_datetime_as_a_max_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMaxTime(1640995200);
        $secondTimeBounds = $firstTimeBounds->withMaxTime(1643673600);

        $this->assertNotEquals(
            $firstTimeBounds->getMaxTime()->toNativeInt(),
            $secondTimeBounds->getMaxTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMaxTime
     */
    public function it_accepts_an_int_as_a_max_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMaxTime(new DateTime('2022-01-01'));
        $secondTimeBounds = $firstTimeBounds->withMaxTime(new DateTime('2000-01-01'));

        $this->assertNotEquals(
            $firstTimeBounds->getMaxTime()->toNativeInt(),
            $secondTimeBounds->getMaxTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::withMaxTime
     */
    public function it_accepts_a_time_point_as_a_max_time_value()
    {
        $firstTimeBounds = (new TimeBounds())->withMaxTime(TimePoint::fromNativeString('2022-01-01'));
        $secondTimeBounds = $firstTimeBounds->withMaxTime(TimePoint::fromNativeString('2000-01-01'));

        $this->assertNotEquals(
            $firstTimeBounds->getMaxTime()->toNativeInt(),
            $secondTimeBounds->getMaxTime()->toNativeInt(),
        );
    }

    /**
     * @test
     * @covers ::oneYear
     */
    public function it_can_create_a_one_year_interval()
    {
        // The oneYear() method creates an interval that is one year + 5 minutes
        $timeBounds = TimeBounds::oneYear();
        $this->assertEquals(31536060, $timeBounds->getInterval()->toNativeInt());
    }

    /**
     * @test
     * @covers ::oneHour
     */
    public function it_can_create_a_one_hour_interval()
    {
        // The oneYear() method creates an interval that is one hour + 1 minute
        $timeBounds = TimeBounds::oneHour();
        $this->assertEquals(3660, $timeBounds->getInterval()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getInterval
     */
    public function it_calculates_intervals()
    {
        $timeBounds = TimeBounds::between('2021-01-01', '2021-01-05');
        $this->assertEquals(345600, $timeBounds->getInterval()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getInterval
     */
    public function it_cannot_calculate_intervals_with_undefined_timepoints()
    {
        $timePoint = TimePoint::now();
        $timeBounds = (new TimeBounds())->withMinTime($timePoint);

        $this->expectException(UnexpectedValueException::class);
        $timeBounds->getInterval();
    }
}
