<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $lastModifiedLedgerSeq; // ledger the LedgerEntry was last changed
    protected LedgerEntryData $data;
    protected LedgerEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->lastModifiedLedgerSeq)) {
            throw new InvalidArgumentException('The ledger entry is missing a last modified ledger sequence number');
        }

        if (!isset($this->data)) {
            throw new InvalidArgumentException('The ledger entry is missing data');
        }

        if (!isset($this->ext)) {
            $this->ext = LedgerEntryExt::empty();
        }

        $xdr->write($this->lastModifiedLedgerSeq)
            ->write($this->data)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerEntry = new static();
        $ledgerEntry->lastModifiedLedgerSeq = $xdr->read(UInt32::class);
        $ledgerEntry->data = $xdr->read(LedgerEntryData::class);
        $ledgerEntry->ext = $xdr->read(LedgerEntryExt::class);

        return $ledgerEntry;
    }

    /**
     * Get the last modified ledger sequence number.
     *
     * @return UInt32
     */
    public function getLastModifiedLedgerSeq(): UInt32
    {
        return $this->lastModifiedLedgerSeq;
    }

    /**
     * Accept a last modified ledger sequence number.
     *
     * @param UInt32 $lastModifiedLedgerSeq
     * @return static
     */
    public function withLastModifiedLedgerSeq(UInt32 $lastModifiedLedgerSeq): static
    {
        $clone = Copy::deep($this);
        $clone->lastModifiedLedgerSeq = Copy::deep($lastModifiedLedgerSeq);

        return $clone;
    }

    /**
     * Get the data.
     *
     * @return LedgerEntryData
     */
    public function getData(): LedgerEntryData
    {
        return $this->data;
    }

    /**
     * Accept data.
     *
     * @param LedgerEntryData $data
     * @return static
     */
    public function withData(LedgerEntryData $data): static
    {
        $clone = Copy::deep($this);
        $clone->data = Copy::deep($data);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return LedgerEntryExt
     */
    public function getExtension(): LedgerEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param LedgerEntryExt $ext
     * @return static
     */
    public function withExtension(LedgerEntryExt $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
