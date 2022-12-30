<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TrustLineEntryV1Ext extends Union implements XdrUnion
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
            2 => TrustLineEntryExtensionV2::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $trustLineEntryV1Ext = new static();
        $trustLineEntryV1Ext->discriminator = 0;
        $trustLineEntryV1Ext->value = null;

        return $trustLineEntryV1Ext;
    }

    /**
     * Create a new instance by wrapping an TrustLineEntryExtensionV2.
     *
     * @param TrustLineEntryExtensionV2 $trustLineEntryExtensionV2
     * @return static
     */
    public static function wrapTrustLineEntryExtensionV2(TrustLineEntryExtensionV2 $trustLineEntryExtensionV2): static
    {
        $trustLineEntryV1Ext = new static();
        $trustLineEntryV1Ext->discriminator = 2;
        $trustLineEntryV1Ext->value = $trustLineEntryExtensionV2;

        return $trustLineEntryV1Ext;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return TrustLineEntryExtensionV2|null
     */
    public function unwrap(): ?TrustLineEntryExtensionV2
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
