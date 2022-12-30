<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class AccountFlags extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */

    // TrustLines are created with authorized set to "false" requiring
    // the issuer to set it for each TrustLine
    public const AUTH_REQUIRED = 1;
    public const AUTH_REQUIRED_FLAG = 'authRequiredFlag';

    // If set, the authorized flag in TrustLines can be cleared
    // otherwise, authorization cannot be revoked
    public const AUTH_REVOCABLE = 2;
    public const AUTH_REVOCABLE_FLAG = 'authRevocableFlag';

    // Once set, causes all AUTH_* flags to be read-only
    public const AUTH_IMMUTABLE = 4;
    public const AUTH_IMMUTABLE_FLAG = 'authImmutableFlag';

    // Trustlines are created with clawback enabled set to "true",
    // and claimable balances created from those trustlines are created
    // with clawback enabled set to "true"
    public const AUTH_CLAWBACK_ENABLED = 8;
    public const AUTH_CLAWBACK_ENABLED_FLAG = 'authClawbackEnabledFlag';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            self::AUTH_REQUIRED         => self::AUTH_REQUIRED_FLAG,
            self::AUTH_REVOCABLE        => self::AUTH_REVOCABLE_FLAG,
            self::AUTH_IMMUTABLE        => self::AUTH_IMMUTABLE_FLAG,
            self::AUTH_CLAWBACK_ENABLED => self::AUTH_CLAWBACK_ENABLED_FLAG,
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
     * Create a new instance pre-selected as AUTH_REQUIRED.
     *
     * @return static
     */
    public static function authRequired(): static
    {
        return (new static())->withValue(self::AUTH_REQUIRED_FLAG);
    }

    /**
     * Create a new instance pre-selected as AUTH_REVOCABLE.
     *
     * @return static
     */
    public static function authRevokable(): static
    {
        return (new static())->withValue(self::AUTH_REVOCABLE_FLAG);
    }

    /**
     * Create a new instance pre-selected as AUTH_IMMUTABLE.
     *
     * @return static
     */
    public static function authImmutable(): static
    {
        return (new static())->withValue(self::AUTH_IMMUTABLE_FLAG);
    }

    /**
     * Create a new instance pre-selected as AUTH_CLAWBACK_ENABLED.
     *
     * @return static
     */
    public static function authClawbackEnabled(): static
    {
        return (new static())->withValue(self::AUTH_CLAWBACK_ENABLED_FLAG);
    }
}
