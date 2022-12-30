<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExt extends Union implements XdrUnion
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
            1 => AccountEntryExtensionV1::class,
        ];
    }

    /**
     * Create a new instance that wraps a null value.
     *
     * @return static
     */
    public static function empty(): static
    {
        $accountEntryExt = new static();
        $accountEntryExt->discriminator = 0;
        $accountEntryExt->value = null;

        return $accountEntryExt;
    }

    /**
     * Create a new instance by wrapping an AccountEntryExtensionV1.
     *
     * @param AccountEntryExtensionV1 $accountEntryExtensionV1
     * @return static
     */
    public static function wrapAccountEntryExtensionV1(AccountEntryExtensionV1 $accountEntryExtensionV1): static
    {
        $accountEntryExt = new static();
        $accountEntryExt->discriminator = 1;
        $accountEntryExt->value = $accountEntryExtensionV1;

        return $accountEntryExt;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return AccountEntryExtensionV1|null
     */
    public function unwrap(): ?AccountEntryExtensionV1
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
