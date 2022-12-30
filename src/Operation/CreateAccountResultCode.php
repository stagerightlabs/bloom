<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class CreateAccountResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CREATE_ACCOUNT_SUCCESS = 'createAccountSuccess';
    public const CREATE_ACCOUNT_MALFORMED = 'createAccountMalformed';
    public const CREATE_ACCOUNT_UNDERFUNDED = 'createAccountUnderfunded';
    public const CREATE_ACCOUNT_LOW_RESERVE = 'createAccountLowReserve';
    public const CREATE_ACCOUNT_ALREADY_EXISTS = 'createAccountAlreadyExist';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CREATE_ACCOUNT_SUCCESS,
            -1 => self::CREATE_ACCOUNT_MALFORMED,
            -2 => self::CREATE_ACCOUNT_UNDERFUNDED,
            -3 => self::CREATE_ACCOUNT_LOW_RESERVE,
            -4 => self::CREATE_ACCOUNT_ALREADY_EXISTS
        ];
    }

    /**
     * Return the selected result type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT_ALREADY_EXIST.
     *
     * @return static
     */
    public static function exists(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT_ALREADY_EXISTS);
    }
}
