<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalUInt32 extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return UInt32::class;
    }

    /**
     * Create a new instance from an UInt32.
     *
     * @param UInt32|int $uint32
     * @throws InvalidArgumentException
     * @return static
     */
    public static function some(UInt32|int $uint32): static
    {
        if (is_int($uint32)) {
            $uint32 = UInt32::of($uint32);
        }

        return self::none()->withValue($uint32);
    }

    /**
     * Return the optional value.
     *
     * @return UInt32|null
     */
    public function unwrap(): ?UInt32
    {
        return $this->getValue();
    }
}
