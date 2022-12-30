<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExtensionV2Ext extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return XDR::INT;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            0 => XDR::VOID,
            3 => AccountEntryExtensionV3::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $accountEntryExtensionV2Ext = new static();
        $accountEntryExtensionV2Ext->discriminator = 0;
        $accountEntryExtensionV2Ext->value = null;

        return $accountEntryExtensionV2Ext;
    }

    /**
     * Create a new instance by wrapping an AccountEntryExtensionV3.
     *
     * @return static
     */
    public static function wrapAccountEntryExtensionV3(AccountEntryExtensionV3 $accountEntryExtensionV3): static
    {
        $accountEntryExtensionV2Ext = new static();
        $accountEntryExtensionV2Ext->discriminator = 3;
        $accountEntryExtensionV2Ext->value = $accountEntryExtensionV3;

        return $accountEntryExtensionV2Ext;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return AccountEntryExtensionV3|null
     */
    public function unwrap(): ?AccountEntryExtensionV3
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
