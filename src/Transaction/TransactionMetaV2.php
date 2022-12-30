<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionMetaV2 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerEntryChanges $txChangesBefore; // Transaction level changes before operations are applied, if any
    protected OperationMetaList $operations; // Meta data for each operation
    protected LedgerEntryChanges $txChangesAfter; // Transaction level changes after operations are applied, if any

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->txChangesBefore)) {
            $this->txChangesBefore = LedgerEntryChanges::empty();
        }

        if (!isset($this->operations)) {
            throw new InvalidArgumentException('The transaction meta v2 is missing operation meta data');
        }

        if (!isset($this->txChangesAfter)) {
            $this->txChangesAfter = LedgerEntryChanges::empty();
        }

        $xdr->write($this->txChangesBefore)
            ->write($this->operations)
            ->write($this->txChangesAfter);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionMetaV2 = new static();
        $transactionMetaV2->txChangesBefore = $xdr->read(LedgerEntryChanges::class);
        $transactionMetaV2->operations = $xdr->read(OperationMetaList::class);
        $transactionMetaV2->txChangesAfter = $xdr->read(LedgerEntryChanges::class);

        return $transactionMetaV2;
    }

    /**
     * Get the list of transaction level changes from before the operations.
     *
     * @return LedgerEntryChanges
     */
    public function getTxChangesBefore(): LedgerEntryChanges
    {
        return $this->txChangesBefore;
    }

    /**
     * Accept a list of transaction level changes from before the operations.
     *
     * @param LedgerEntryChanges $txChangesBefore
     * @return static
     */
    public function withTxChangesBefore(LedgerEntryChanges $txChangesBefore): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txChangesBefore = Copy::deep($txChangesBefore);

        return $clone;
    }

    /**
     * Get the list of operation meta data.
     *
     * @return OperationMetaList
     */
    public function getOperations(): OperationMetaList
    {
        return $this->operations;
    }

    /**
     * Accept a list of operation meta data.
     *
     * @param OperationMetaList $operations
     * @return static
     */
    public function withOperations(OperationMetaList $operations): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->operations = Copy::deep($operations);

        return $clone;
    }

    /**
     * Get the list of transaction level changes from after the operations.
     *
     * @return LedgerEntryChanges
     */
    public function getTxChangesAfter(): LedgerEntryChanges
    {
        return $this->txChangesAfter;
    }

    /**
     * Accept a list of transaction level change from after the operations.
     *
     * @param LedgerEntryChanges $txChangesAfter
     * @return static
     */
    public function withTxChangesAfter(LedgerEntryChanges $txChangesAfter): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txChangesAfter = Copy::deep($txChangesAfter);

        return $clone;
    }
}
