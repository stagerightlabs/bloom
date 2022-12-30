<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionSet implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $previousLedgerHash;
    protected TransactionEnvelopeList $transactions;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->previousLedgerHash)) {
            throw new InvalidArgumentException('The transaction set is missing a previous ledger hash');
        }

        if (!isset($this->transactions)) {
            $this->transactions = TransactionEnvelopeList::empty();
        }

        $xdr->write($this->previousLedgerHash)->write($this->transactions);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionSet = new static();
        $transactionSet->previousLedgerHash = $xdr->read(Hash::class);
        $transactionSet->transactions = $xdr->read(TransactionEnvelopeList::class);

        return $transactionSet;
    }

    /**
     * Get the previous ledger hash.
     *
     * @return Hash
     */
    public function getPreviousLedgerHash(): Hash
    {
        return $this->previousLedgerHash;
    }

    /**
     * Accept a previous ledger hash.
     *
     * @param Hash $previousLedgerHash
     * @return static
     */
    public function withPreviousLedgerHash(Hash $previousLedgerHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->previousLedgerHash = Copy::deep($previousLedgerHash);

        return $clone;
    }

    /**
     * Get the list of transactions.
     *
     * @return TransactionEnvelopeList
     */
    public function getTransactions(): TransactionEnvelopeList
    {
        return $this->transactions;
    }

    /**
     * Accept a list of transactions.
     *
     * @param TransactionEnvelopeList $transactions
     * @return static
     */
    public function withTransactions(TransactionEnvelopeList $transactions): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->transactions = Copy::deep($transactions);

        return $clone;
    }
}
