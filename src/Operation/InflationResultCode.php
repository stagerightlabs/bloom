<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class InflationResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const INFLATION_SUCCESS = 'inflationSuccess';
    public const INFLATION_NOT_TIME = 'inflationNotTime';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::INFLATION_SUCCESS,
            -1 => self::INFLATION_NOT_TIME,
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
     * Create a new instance pre-selected as INFLATION_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::INFLATION_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as INFLATION_NOT_TIME.
     *
     * @return static
     */
    public static function notTime(): static
    {
        return (new static())->withValue(self::INFLATION_NOT_TIME);
    }
}
