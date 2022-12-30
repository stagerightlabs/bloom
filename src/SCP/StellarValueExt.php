<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Ledger\LedgerCloseValueSignature;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class StellarValueExt extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return StellarValueType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            StellarValueType::STELLAR_VALUE_BASIC  => XDR::VOID,
            StellarValueType::STELLAR_VALUE_SIGNED => LedgerCloseValueSignature::class,
        ];
    }

    /**
     * Create an empty instance.
     *
     * @return static
     */
    public static function basic(): static
    {
        $stellarValueExt = new static();
        $stellarValueExt->discriminator = StellarValueType::basic();
        $stellarValueExt->value = null;

        return $stellarValueExt;
    }

    /**
     * Create a new instance by wrapping a LedgerCloseValueSignature.
     *
     * @param LedgerCloseValueSignature $ledgerCloseValueSignature
     * @return static
     */
    public static function wrapLedgerCloseValueSignature(LedgerCloseValueSignature $ledgerCloseValueSignature): static
    {
        $stellarValueExt = new static();
        $stellarValueExt->discriminator = StellarValueType::signed();
        $stellarValueExt->value = $ledgerCloseValueSignature;

        return $stellarValueExt;
    }

    /**
     * Return the underlying value, if present.
     *
     * @return LedgerCloseValueSignature|null
     */
    public function unwrap(): ?LedgerCloseValueSignature
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
