<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\TransactionResultSet;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionHistoryResultEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerSeq;
    protected TransactionResultSet $txResultSet;
    protected TransactionHistoryResultEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerSeq)) {
            throw new InvalidArgumentException('The transaction history result entry is missing a ledger sequence');
        }

        if (!isset($this->txResultSet)) {
            throw new InvalidArgumentException('The transaction history result entry is missing a transaction result set');
        }

        if (!isset($this->ext)) {
            $this->ext = TransactionHistoryResultEntryExt::empty();
        }

        $xdr->write($this->ledgerSeq)
            ->write($this->txResultSet)
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
        $transactionHistoryResultEntry = new static();
        $transactionHistoryResultEntry->ledgerSeq = $xdr->read(UInt32::class);
        $transactionHistoryResultEntry->txResultSet = $xdr->read(TransactionResultSet::class);
        $transactionHistoryResultEntry->ext = $xdr->read(TransactionHistoryResultEntryExt::class);

        return $transactionHistoryResultEntry;
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
     * Get the transaction result set.
     *
     * @return TransactionResultSet
     */
    public function getTransactionResultSet(): TransactionResultSet
    {
        return $this->txResultSet;
    }

    /**
     * Accept a transaction result set.
     *
     * @param TransactionResultSet $txResultSet
     * @return static
     */
    public function withTransactionResultSet(TransactionResultSet $txResultSet): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txResultSet = Copy::deep($txResultSet);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TransactionHistoryResultEntryExt
     */
    public function getExtension(): TransactionHistoryResultEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TransactionHistoryResultEntryExt $ext
     * @return static
     */
    public function withExtension(TransactionHistoryResultEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
