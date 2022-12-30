<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionSetV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $previousLedgerHash;
    protected TransactionPhaseList $phases;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->previousLedgerHash)) {
            throw new InvalidArgumentException('The TransactionSetV1 is missing a previous ledger hash');
        }

        if (!isset($this->phases)) {
            $this->phases = TransactionPhaseList::empty();
        }

        $xdr->write($this->previousLedgerHash)
            ->write($this->phases);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionSetV1 = new static();
        $transactionSetV1->previousLedgerHash = $xdr->read(Hash::class);
        $transactionSetV1->phases = $xdr->read(TransactionPhaseList::class);

        return $transactionSetV1;
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
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->previousLedgerHash = Copy::deep($previousLedgerHash);

        return $clone;
    }

    /**
     * Get the transaction phases.
     *
     * @return TransactionPhaseList
     */
    public function getPhases(): TransactionPhaseList
    {
        return $this->phases;
    }

    /**
     * Accept a list of transaction phases.
     *
     * @param TransactionPhaseList $phases
     * @return static
     */
    public function withPhases(TransactionPhaseList $phases): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->phases = Copy::deep($phases);

        return $clone;
    }
}
