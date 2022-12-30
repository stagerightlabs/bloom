<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class TrustLineFlags extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const AUTHORIZED = 1;
    public const AUTHORIZED_FLAG = 'authorizedFlag';
    public const AUTHORIZED_TO_MAINTAIN_LIABILITIES = 2;
    public const AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG = 'authorizedToMaintainLiabilitiesFlag';
    public const TRUSTLINE_CLAWBACK_ENABLED = 4;
    public const TRUSTLINE_CLAWBACK_ENABLED_FLAG = 'trustlineClawbackEnabledFlag';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            self::AUTHORIZED                         => self::AUTHORIZED_FLAG,
            self::AUTHORIZED_TO_MAINTAIN_LIABILITIES => self::AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG,
            self::TRUSTLINE_CLAWBACK_ENABLED         => self::TRUSTLINE_CLAWBACK_ENABLED_FLAG,
        ];
    }

    /**
     * Return the selected flag type.
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
     * Create a new instance pre-selected as AUTHORIZED_FLAG.
     *
     * @return static
     */
    public static function authorized(): static
    {
        return (new static())->withValue(self::AUTHORIZED_FLAG);
    }

    /**
     * Create a new instance pre-selected as AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG.
     *
     * @return static
     */
    public static function authorizedToMaintainLiabilities(): static
    {
        return (new static())->withValue(self::AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG);
    }

    /**
     * Create a new instance pre-selected as TRUSTLINE_CLAWBACK_ENABLED_FLAG.
     *
     * @return static
     */
    public static function trustlineClawbackEnabled(): static
    {
        return (new static())->withValue(self::TRUSTLINE_CLAWBACK_ENABLED_FLAG);
    }
}
