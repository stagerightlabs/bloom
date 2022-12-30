<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExtensionV1Ext extends Union implements XdrUnion
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
            2 => AccountEntryExtensionV2::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $accountEntryExtensionV1Ext = new static();
        $accountEntryExtensionV1Ext->discriminator = 0;
        $accountEntryExtensionV1Ext->value = null;

        return $accountEntryExtensionV1Ext;
    }

    /**
     * Create a new instance by wrapping an AccountEntryExtensionV2.
     *
     * @param AccountEntryExtensionV2 $accountEntryExtensionV2
     * @return static
     */
    public static function wrapAccountEntryExtensionV2(AccountEntryExtensionV2 $accountEntryExtensionV2): static
    {
        $accountEntryExtensionV1Ext = new static();
        $accountEntryExtensionV1Ext->discriminator = 2;
        $accountEntryExtensionV1Ext->value = $accountEntryExtensionV2;

        return $accountEntryExtensionV1Ext;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return AccountEntryExtensionV2|null
     */
    public function unwrap(): ?AccountEntryExtensionV2
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
