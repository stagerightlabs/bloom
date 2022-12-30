<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionHistoryEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerSeq;
    protected TransactionSet $txSet;
    protected TransactionHistoryEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerSeq)) {
            throw new InvalidArgumentException('The transaction history entry is missing a ledger sequence number');
        }

        if (!isset($this->txSet)) {
            throw new InvalidArgumentException('The transaction history entry is missing a transaction set');
        }

        if (!isset($this->ext)) {
            $this->ext = TransactionHistoryEntryExt::empty();
        }

        $xdr->write($this->ledgerSeq)
            ->write($this->txSet)
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
        $transactionHistoryEntry = new static();
        $transactionHistoryEntry->ledgerSeq = $xdr->read(UInt32::class);
        $transactionHistoryEntry->txSet = $xdr->read(TransactionSet::class);
        $transactionHistoryEntry->ext = $xdr->read(TransactionHistoryEntryExt::class);

        return $transactionHistoryEntry;
    }

    /**
     * Get the ledger sequence number.
     *
     * @return UInt32
     */
    public function getLedgerSeq(): UInt32
    {
        return $this->ledgerSeq;
    }

    /**
     * Accept a ledger sequence number.
     *
     * @param UInt32|int $ledgerSeq
     * @return static
     */
    public function withLedgerSeq(UInt32|int $ledgerSeq): static
    {
        if (is_int($ledgerSeq)) {
            $ledgerSeq = UInt32::of($ledgerSeq);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->ledgerSeq = Copy::deep($ledgerSeq);

        return $clone;
    }

    /**
     * Get the transaction set.
     *
     * @return TransactionSet
     */
    public function getTransactionSet(): TransactionSet
    {
        return $this->txSet;
    }

    /**
     * Accept a transaction set.
     *
     * @param TransactionSet $txSet
     * @return static
     */
    public function withTransactionSet(TransactionSet $txSet): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txSet = Copy::deep($txSet);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TransactionHistoryEntryExt
     */
    public function getExtension(): TransactionHistoryEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TransactionHistoryEntryExt $ext
     * @return static
     */
    public function withExtension(TransactionHistoryEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
