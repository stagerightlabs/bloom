<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class StellarValueType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const STELLAR_VALUE_BASIC = 'stellarValueBasic';
    public const STELLAR_VALUE_SIGNED = 'stellarValueSigned';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::STELLAR_VALUE_BASIC,
            1 => self::STELLAR_VALUE_SIGNED,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as STELLAR_VALUE_BASIC.
     *
     * @return static
     */
    public static function basic(): static
    {
        return (new static())->withValue(self::STELLAR_VALUE_BASIC);
    }

    /**
     * Create a new instance pre-selected as STELLAR_VALUE_SIGNED.
     *
     * @return static
     */
    public static function signed(): static
    {
        return (new static())->withValue(self::STELLAR_VALUE_SIGNED);
    }
}
