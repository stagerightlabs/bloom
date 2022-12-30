<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;

final class TimePoint extends UInt64
{
    /**
     * Create a new time point representing the current unix timestamp.
     *
     * @return static
     */
    public static function now(): static
    {
        return static::of(time());
    }

    /**
     * Create a new time point from a DateTime instance.
     *
     * @param DateTime $dt
     * @return static
     */
    public static function fromDateTime(DateTime $dt): static
    {
        return static::of($dt->format('U'));
    }

    /**
     * Create a new time point from a unix epoch.
     *
     * @param int $epoch
     * @return static
     */
    public static function fromUnixEpoch(int $epoch): static
    {
        return static::of($epoch);
    }

    /**
     * Convert the time point to an integer representing a Unix epoch.
     *
     * @return int
     */
    public function toUnixEpoch(): int
    {
        return $this->toNativeInt();
    }

    /**
     * Create a new time point from a date string, as parsed by DateTime.
     *
     * @param string $date
     * @throws InvalidArgumentException
     * @return static
     */
    public static function fromNativeString(string $date): static
    {
        $timestamp = strtotime($date);

        if (!$timestamp) {
            throw new InvalidArgumentException("Unable to parse '{$date}' as a unix time stamp.");
        }

        return static::of($timestamp);
    }

    /**
     * Convert the time point to a DateTime instance.
     *
     * @return DateTime
     */
    public function toDateTime(): DateTime
    {
        return new DateTime('@' . strval($this->toNativeInt()));
    }
}
