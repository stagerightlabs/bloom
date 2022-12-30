<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TimeBounds implements XdrStruct
{
    /**
     * Properties
     */
    protected TimePoint $minTime;
    protected TimePoint $maxTime;

    /**
     * Write the struct to XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->minTime)) {
            throw new InvalidArgumentException('The time bounds is missing a lower time point');
        }

        if (!isset($this->maxTime)) {
            throw new InvalidArgumentException('The time bounds is missing an upper time point');
        }

        $xdr->write($this->minTime, TimePoint::class)
            ->write($this->maxTime, TimePoint::class);
    }

    /**
     * Read the struct from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())
            ->withMinTime($xdr->read(TimePoint::class))
            ->withMaxTime($xdr->read(TimePoint::class));
    }

    /**
     * Get the lower time bound.
     *
     * @return ?TimePoint
     */
    public function getMinTime(): ?TimePoint
    {
        return $this->minTime;
    }

    /**
     * Set the lower time bound.
     *
     * @param TimePoint|DateTime|string $minTime
     *
     * @return static
     */
    public function withMinTime(TimePoint|DateTime|string|int $minTime): static
    {
        if (is_string($minTime)) {
            $minTime = TimePoint::fromNativeString($minTime);
        } elseif (is_int($minTime)) {
            $minTime = TimePoint::fromUnixEpoch($minTime);
        } elseif ($minTime instanceof DateTime) {
            $minTime = TimePoint::fromDateTime($minTime);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->minTime = Copy::deep($minTime);

        return $clone;
    }

    /**
     * Get the upper time bound.
     *
     * @return ?TimePoint
     */
    public function getMaxTime(): ?TimePoint
    {
        return $this->maxTime;
    }

    /**
     * Set the upper time bound.
     *
     * @param TimePoint|DateTime|string|int $maxTime
     *
     * @return static
     */
    public function withMaxTime(TimePoint|DateTime|string|int $maxTime): static
    {
        if (is_string($maxTime)) {
            $maxTime = TimePoint::fromNativeString($maxTime);
        } elseif (is_int($maxTime)) {
            $maxTime = TimePoint::fromUnixEpoch($maxTime);
        } elseif ($maxTime instanceof DateTime) {
            $maxTime = TimePoint::fromDateTime($maxTime);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->maxTime = Copy::deep($maxTime);

        return $clone;
    }

    /**
     * Create a TimeBounds instance from a start and end date.
     *
     * @param TimePoint|DateTime|string $start
     * @param TimePoint|DateTime|string $end
     * @return static
     */
    public static function between(TimePoint|DateTime|string $start, TimePoint|DateTime|string $end): static
    {
        return (new static())
            ->withMinTime($start)
            ->withMaxTime($end);
    }

    /**
     * Generate a TimeBounds instance that starts one minute ago and ends in one year.
     *
     * @return static
     */
    public static function oneYear(): static
    {
        return (new static())->between(
            TimePoint::fromNativeString('-1 minute'),
            TimePoint::fromNativeString('+1 year')
        );
    }

    /**
     * Generate a TimeBounds instance that starts one minute ago and ends in one hour.
     *
     * @return static
     */
    public static function oneHour(): static
    {
        return (new static())->between(
            TimePoint::fromNativeString('-1 minute'),
            TimePoint::fromNativeString('+1 hour')
        );
    }

    /**
     * The number of seconds between the start and end of the time bounds.
     *
     * @return UInt64
     */
    public function getInterval(): UInt64
    {
        if (!isset($this->minTime) || !isset($this->maxTime)) {
            throw new UnexpectedValueException('Cannot calculate interval for undefined time bounds.');
        }

        return UInt64::of($this->maxTime->minus($this->minTime->toBigInteger()));
    }
}
