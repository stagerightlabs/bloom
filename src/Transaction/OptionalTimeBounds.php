<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalTimeBounds extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return TimeBounds::class;
    }

    /**
     * Instantiate a new instance that wraps a TimeBounds object.
     *
     * @param TimeBounds $timeBounds
     * @return static
     */
    public static function some(TimeBounds $timeBounds): static
    {
        return (new static())->withValue($timeBounds);
    }

    /**
     * Return the time bounds.
     *
     * @return TimeBounds|null
     */
    public function unwrap(): ?TimeBounds
    {
        return $this->getValue();
    }
}
