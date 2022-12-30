<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class CreateClaimableBalanceResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CREATE_CLAIMABLE_BALANCE_SUCCESS = 'createClaimableBalanceSuccess';
    public const CREATE_CLAIMABLE_BALANCE_MALFORMED = 'createClaimableBalanceMalformed';
    public const CREATE_CLAIMABLE_BALANCE_LOW_RESERVE = 'createClaimableBalanceLowReserve';
    public const CREATE_CLAIMABLE_BALANCE_NO_TRUST = 'createClaimableBalanceNoTrust';
    public const CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED = 'createClaimableBalanceNotAuthorized';
    public const CREATE_CLAIMABLE_BALANCE_UNDERFUNDED = 'createClaimableBalanceUnderfunded';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CREATE_CLAIMABLE_BALANCE_SUCCESS,
            -1 => self::CREATE_CLAIMABLE_BALANCE_MALFORMED,
            -2 => self::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE,
            -3 => self::CREATE_CLAIMABLE_BALANCE_NO_TRUST,
            -4 => self::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED,
            -5 => self::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED,
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
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED);
    }
}
