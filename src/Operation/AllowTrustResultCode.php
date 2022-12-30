<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class AllowTrustResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ALLOW_TRUST_SUCCESS = 'allowTrustSuccess';
    public const ALLOW_TRUST_MALFORMED = 'allowTrustMalformed'; // asset is not ASSET_TYPE_ALPHANUM
    public const ALLOW_TRUST_NO_TRUST_LINE = 'allowTrustNoTrustLine'; // trustor does not have a trustline
    public const ALLOW_TRUST_TRUST_NOT_REQUIRED = 'allowTrustTrustNotRequired'; // source account does not require trust
    public const ALLOW_TRUST_CANT_REVOKE = 'allowTrustCantRevoke'; // source account can't revoke trust
    public const ALLOW_TRUST_SELF_NOT_ALLOWED = 'allowTrustSelfNotAllowed'; // trusting self is not allowed
    public const ALLOW_TRUST_LOW_RESERVE = 'allowTrustLowReserve'; // claimable balances can't be created on revoke due to low reserves

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::ALLOW_TRUST_SUCCESS,
            -1 => self::ALLOW_TRUST_MALFORMED,
            -2 => self::ALLOW_TRUST_NO_TRUST_LINE,
            -3 => self::ALLOW_TRUST_TRUST_NOT_REQUIRED,
            -4 => self::ALLOW_TRUST_CANT_REVOKE,
            -5 => self::ALLOW_TRUST_SELF_NOT_ALLOWED,
            -6 => self::ALLOW_TRUST_LOW_RESERVE,
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
     * Create a new instance pre-selected as ALLOW_TRUST_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_NO_TRUST_LINE.
     *
     * @return static
     */
    public static function noTrustLine(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_NO_TRUST_LINE);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_TRUST_NOT_REQUIRED.
     *
     * @return static
     */
    public static function trustNotRequired(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_TRUST_NOT_REQUIRED);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_CANT_REVOKE.
     *
     * @return static
     */
    public static function cantRevoke(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_CANT_REVOKE);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_SELF_NOT_ALLOWED.
     *
     * @return static
     */
    public static function selfNotAllowed(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_SELF_NOT_ALLOWED);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST_LOW_RESERVE);
    }
}
