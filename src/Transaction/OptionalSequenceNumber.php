<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalSequenceNumber extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return SequenceNumber::class;
    }

    /**
     * Instantiate a new instance that wraps a LedgerBounds object.
     *
     * @param SequenceNumber $sequenceNumber
     * @return static
     */
    public static function some(SequenceNumber $sequenceNumber): static
    {
        return (new static())->withValue($sequenceNumber);
    }

    /**
     * Return the ledger bounds.
     *
     * @return SequenceNumber|null
     */
    public function unwrap(): ?SequenceNumber
    {
        return $this->getValue();
    }
}
