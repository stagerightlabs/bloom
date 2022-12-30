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

final class TransactionMetaV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerEntryChanges $txChanges; // Transaction level changes, if any
    protected OperationMetaList $operations; // meta for each operation

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->txChanges)) {
            $this->txChanges = LedgerEntryChanges::empty();
        }

        if (!isset($this->operations)) {
            throw new InvalidArgumentException('The transaction meta v1 is missing operation meta data');
        }

        $xdr->write($this->txChanges)->write($this->operations);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionMetaV1 = new static();
        $transactionMetaV1->txChanges = $xdr->read(LedgerEntryChanges::class);
        $transactionMetaV1->operations = $xdr->read(OperationMetaList::class);

        return $transactionMetaV1;
    }

    /**
     * Get the list of transaction level changes.
     *
     * @return LedgerEntryChanges
     */
    public function getTxChanges(): LedgerEntryChanges
    {
        return $this->txChanges;
    }

    /**
     * Accept a list of transaction level changes.
     *
     * @param LedgerEntryChanges $txChanges
     * @return static
     */
    public function withTxChanges(LedgerEntryChanges $txChanges): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txChanges = Copy::deep($txChanges);

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
}
