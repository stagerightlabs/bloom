<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimClaimableBalanceResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAIM_CLAIMABLE_BALANCE_SUCCESS = 'claimClaimableBalanceSuccess';
    public const CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST = 'claimClaimableBalanceDoesNotExist';
    public const CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM = 'claimClaimableBalanceCannotClaim';
    public const CLAIM_CLAIMABLE_BALANCE_LINE_FULL = 'claimClaimableBalanceLineFull';
    public const CLAIM_CLAIMABLE_BALANCE_NO_TRUST = 'claimClaimableBalanceNoTrust';
    public const CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED = 'claimClaimableBalanceNotAuthorized';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CLAIM_CLAIMABLE_BALANCE_SUCCESS,
            -1 => self::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST,
            -2 => self::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM,
            -3 => self::CLAIM_CLAIMABLE_BALANCE_LINE_FULL,
            -4 => self::CLAIM_CLAIMABLE_BALANCE_NO_TRUST,
            -5 => self::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED,
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
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST.
     *
     * @return static
     */
    public static function doesNotExist(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM.
     *
     * @return static
     */
    public static function cannotClaim(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED);
    }
}
