<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClawbackClaimableBalanceResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAWBACK_CLAIMABLE_BALANCE_SUCCESS = 'clawbackClaimableBalanceSuccess';
    public const CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST = 'clawbackClaimableBalanceDoesNotExist';
    public const CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER = 'clawbackClaimableBalanceNotIssuer';
    public const CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED = 'clawbackClaimableBalanceNotClawbackEnabled';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS,
            -1 => self::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST,
            -2 => self::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER,
            -3 => self::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED,
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
     * Create a new instance pre-selected as CLAWBACK_CLAIMABLE_BALANCE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST.
     *
     * @return static
     */
    public static function doesNotExist(): static
    {
        return (new static())->withValue(self::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER.
     *
     * @return static
     */
    public static function notIssuer(): static
    {
        return (new static())->withValue(self::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED.
     *
     * @return static
     */
    public static function notClawbackEnabled(): static
    {
        return (new static())->withValue(self::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED);
    }
}
