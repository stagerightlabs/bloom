<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ClaimableBalanceEntryExt extends Union implements XdrUnion
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
            1 => ClaimableBalanceEntryExtensionV1::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $claimableBalanceEntryExt = new static();
        $claimableBalanceEntryExt->discriminator = 0;
        $claimableBalanceEntryExt->value = null;

        return $claimableBalanceEntryExt;
    }

    /**
     * Create a new instance by wrapping a ClaimableBalanceEntryExtensionV1.
     *
     * @param ClaimableBalanceEntryExtensionV1 $claimableBalanceEntryExtensionV1
     * @return static
     */
    public static function wrapClaimableBalanceEntryExtensionV1(ClaimableBalanceEntryExtensionV1 $claimableBalanceEntryExtensionV1): static
    {
        $claimableBalanceEntryExt = new static();
        $claimableBalanceEntryExt->discriminator = 1;
        $claimableBalanceEntryExt->value = $claimableBalanceEntryExtensionV1;

        return $claimableBalanceEntryExt;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return ClaimableBalanceEntryExtensionV1|null
     */
    public function unwrap(): ?ClaimableBalanceEntryExtensionV1
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
