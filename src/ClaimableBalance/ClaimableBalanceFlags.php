<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimableBalanceFlags extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */

    // If set, the issuer account of the asset held by the claimable balance may
    // clawback the claimable balance
    public const CLAIMABLE_BALANCE_CLAWBACK_ENABLED = 1;
    public const CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG = 'claimableBalanceCLawbackEnabledFlag';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            self::CLAIMABLE_BALANCE_CLAWBACK_ENABLED => self::CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG,
        ];
    }

    /**
     * Return the selected claimable balance flags
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Retrieve the integer value of the selected flag.
     *
     * @return integer
     */
    public function toNativeInt(): int
    {
        return $this->getIndex();
    }

    /**
     * Create a new instance pre-selected as CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG.
     *
     * @return static
     */
    public static function clawbackEnabled(): static
    {
        return (new static())->withValue(self::CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG);
    }
}
