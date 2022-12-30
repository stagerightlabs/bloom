<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerBounds implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $minLedgerOffset;
    protected UInt32 $maxLedgerOffset;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->getMinLedgerOffset())->write($this->getMaxLedgerOffset());
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())
            ->withMinLedgerOffset($xdr->read(UInt32::class))
            ->withMaxLedgerOffset($xdr->read(UInt32::class));
    }

    /**
     * Create a ledger bounds instance from two ledger numbers.
     *
     * @param UInt32|int $min
     * @param UInt32|int $max
     * @return static
     */
    public static function between(UInt32|int $min = 0, UInt32|int $max = 0): static
    {
        if (is_int($min)) {
            $min = UInt32::of($min);
        }

        if (is_int($max)) {
            $max = UInt32::of($max);
        }

        $ledgerBounds = new LedgerBounds();
        $ledgerBounds->minLedgerOffset = $min;
        $ledgerBounds->maxLedgerOffset = $max;

        return $ledgerBounds;
    }

    /**
     * Get the minimum ledger offset.
     *
     * @return UInt32
     */
    public function getMinLedgerOffset(): UInt32
    {
        if (!isset($this->minLedgerOffset)) {
            $this->minLedgerOffset = UInt32::of(0);
        }

        return $this->minLedgerOffset;
    }

    /**
     * Accept a minimum ledger offset.
     *
     * @param UInt32|int $minLedgerOffset
     * @return static
     */
    public function withMinLedgerOffset(UInt32|int $minLedgerOffset): static
    {
        if (is_int($minLedgerOffset)) {
            $minLedgerOffset = UInt32::of($minLedgerOffset);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->minLedgerOffset = Copy::deep($minLedgerOffset);

        return $clone;
    }

    /**
     * Get the maximum ledger offset.
     *
     * @return UInt32
     */
    public function getMaxLedgerOffset(): UInt32
    {
        if (!isset($this->maxLedgerOffset)) {
            $this->maxLedgerOffset = UInt32::of(0);
        }

        return $this->maxLedgerOffset;
    }

    /**
     * Accept a maximum ledger offset.
     *
     * @param UInt32|int $maxLedger
     * @return static
     */
    public function withMaxLedgerOffset(UInt32|int $maxLedger): static
    {
        if (is_int($maxLedger)) {
            $maxLedger = UInt32::of($maxLedger);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->maxLedgerOffset = Copy::deep($maxLedger);

        return $clone;
    }
}
