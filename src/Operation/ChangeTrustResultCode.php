<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ChangeTrustResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CHANGE_TRUST_SUCCESS = 'changeTrustSuccess';
    public const CHANGE_TRUST_MALFORMED = 'changeTrustMalformed'; // bad input
    public const CHANGE_TRUST_NO_ISSUER = 'changeTrustNoIssuer'; // could not find issuer
    public const CHANGE_TRUST_INVALID_LIMIT = 'changeTrustInvalidLimit'; // cannot drop limit below balance
    public const CHANGE_TRUST_LOW_RESERVE = 'changeTrustLowReserve'; // not enough funds to create a new trust line
    public const CHANGE_TRUST_SELF_NOT_ALLOWED = 'changeTrustSelfNotAllowed'; // trusting self is not allowed
    public const CHANGE_TRUST_TRUST_LINE_MISSING = 'changeTrustTrustLineMissing'; // Asset trustline is missing for pool
    public const CHANGE_TRUST_CANNOT_DELETE = 'changeTrustCannotDelete'; // Asset trustline is still referenced in a pool
    public const CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES = 'changeTrustNotAuthMaintainLiabilities'; // Asset trustline is de-authorized

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CHANGE_TRUST_SUCCESS,
            -1 => self::CHANGE_TRUST_MALFORMED,
            -2 => self::CHANGE_TRUST_NO_ISSUER,
            -3 => self::CHANGE_TRUST_INVALID_LIMIT,
            -4 => self::CHANGE_TRUST_LOW_RESERVE,
            -5 => self::CHANGE_TRUST_SELF_NOT_ALLOWED,
            -6 => self::CHANGE_TRUST_TRUST_LINE_MISSING,
            -7 => self::CHANGE_TRUST_CANNOT_DELETE,
            -8 => self::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES,
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
     * Create a new instance pre-selected as CHANGE_TRUST_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_NO_ISSUER.
     *
     * @return static
     */
    public static function noIssuer(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_INVALID_LIMIT.
     *
     * @return static
     */
    public static function invalidLimit(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_INVALID_LIMIT);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_SELF_NOT_ALLOWED.
     *
     * @return static
     */
    public static function selfNotAllowed(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_SELF_NOT_ALLOWED);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_TRUST_LINE_MISSING.
     *
     * @return static
     */
    public static function trustlineMissing(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_TRUST_LINE_MISSING);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_CANNOT_DELETE.
     *
     * @return static
     */
    public static function cannotDelete(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_CANNOT_DELETE);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES.
     *
     * @return static
     */
    public static function notAuthMaintainLiabilities(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES);
    }
}
