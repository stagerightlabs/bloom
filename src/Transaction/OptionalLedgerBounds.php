<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalLedgerBounds extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return LedgerBounds::class;
    }

    /**
     * Instantiate a new instance that wraps a LedgerBounds object.
     *
     * @param LedgerBounds $ledgerBounds
     * @return static
     */
    public static function some(LedgerBounds $ledgerBounds): static
    {
        return (new static())->withValue($ledgerBounds);
    }

    /**
     * Return the ledger bounds.
     *
     * @return LedgerBounds|null
     */
    public function unwrap(): ?LedgerBounds
    {
        return $this->getValue();
    }
}
