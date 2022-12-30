<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalString32 extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return String32::class;
    }

    /**
     * Instantiate an instance from an String32.
     *
     * @param String32 $string32
     * @throws InvalidArgumentException
     * @return static
     */
    public static function some(String32|string $string32): static
    {
        if (is_string($string32)) {
            $string32 = String32::of($string32);
        }

        return self::none()->withValue($string32);
    }

    /**
     * Return the optional value.
     *
     * @return String32|null
     */
    public function unwrap(): ?String32
    {
        return $this->getValue();
    }
}
