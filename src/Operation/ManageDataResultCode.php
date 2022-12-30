<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ManageDataResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const MANAGE_DATA_SUCCESS = 'manageDataSuccess';
    public const MANAGE_DATA_NOT_SUPPORTED_YET = 'manageDataNotSupportedYet'; // The network hasn't moved to this protocol change yet
    public const MANAGE_DATA_NAME_NOT_FOUND = 'manageDataNameNotFound'; // Trying to remove a Data Entry that isn't there
    public const MANAGE_DATA_LOW_RESERVE = 'manageDataLowReserve'; // Not enough funds to create a new Data Entry
    public const MANAGE_DATA_INVALID_NAME = 'manageDataInvalidName'; // Name not a valid string

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::MANAGE_DATA_SUCCESS,
            -1 => self::MANAGE_DATA_NOT_SUPPORTED_YET,
            -2 => self::MANAGE_DATA_NAME_NOT_FOUND,
            -3 => self::MANAGE_DATA_LOW_RESERVE,
            -4 => self::MANAGE_DATA_INVALID_NAME,
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
     * Create a new instance pre-selected as MANAGE_DATA_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::MANAGE_DATA_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as MANGE_DATA_NOT_SUPPORTED_YET.
     *
     * @return static
     */
    public static function notSupportedYet(): static
    {
        return (new static())->withValue(self::MANAGE_DATA_NOT_SUPPORTED_YET);
    }

    /**
     * Create a new instance pre-selected as MANAGE_DATA_NAME_NOT_FOUND.
     *
     * @return static
     */
    public static function nameNotFound(): static
    {
        return (new static())->withValue(self::MANAGE_DATA_NAME_NOT_FOUND);
    }

    /**
     * Create a new instance pre-selected as MANAGE_DATA_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::MANAGE_DATA_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as MANAGE_DATA_INVALID_NAME.
     *
     * @return static
     */
    public static function invalidName(): static
    {
        return (new static())->withValue(self::MANAGE_DATA_INVALID_NAME);
    }
}
