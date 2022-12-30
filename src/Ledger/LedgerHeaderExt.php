<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class LedgerHeaderExt extends Union implements XdrUnion
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
            1 => LedgerHeaderExtensionV1::class,
        ];
    }

    /**
     * Create an empty instance.
     *
     * @return static
     */
    public static function empty(): static
    {
        $ledgerHeaderExt = new static();
        $ledgerHeaderExt->discriminator = 0;
        $ledgerHeaderExt->value = null;

        return $ledgerHeaderExt;
    }

    /**
     * Create a new instance by wrapping a ledger header extension V1.
     *
     * @param LedgerHeaderExtensionV1 $ledgerHeaderExtensionV1
     * @return static
     */
    public static function wrapLedgerHeaderExtensionV1(LedgerHeaderExtensionV1 $ledgerHeaderExtensionV1): static
    {
        $ledgerHeaderExt = new static();
        $ledgerHeaderExt->discriminator = 1;
        $ledgerHeaderExt->value = $ledgerHeaderExtensionV1;

        return $ledgerHeaderExt;
    }

    /**
     * Return the underlying value.
     *
     * @return LedgerHeaderExtensionV1|null
     */
    public function unwrap(): ?LedgerHeaderExtensionV1
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
