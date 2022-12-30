<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalInt64 extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return Int64::class;
    }

    /**
     * Create an instance from an Int64.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     * If given an Int64 it will return a new clone instance.
     *
     * @param Int64|ScaledAmount|int|string $value
     * @return static
     */
    public static function some(Int64|ScaledAmount|int|string $value): static
    {
        return self::none()->withValue(Int64::normalize($value));
    }

    /**
     * Return the optional value.
     *
     * @return Int64|null
     */
    public function unwrap(): ?Int64
    {
        return $this->getValue();
    }
}
