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

final class TransactionResultPair implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $transactionHash;
    protected TransactionResult $result;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->transactionHash)) {
            throw new InvalidArgumentException('The transaction result pair is missing a transaction hash');
        }

        if (!isset($this->result)) {
            throw new InvalidArgumentException('The transaction result pair is missing a result');
        }

        $xdr->write($this->transactionHash)
            ->write($this->result);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionResultPair = new static();
        $transactionResultPair->transactionHash = $xdr->read(Hash::class);
        $transactionResultPair->result = $xdr->read(TransactionResult::class);

        return $transactionResultPair;
    }

    /**
     * Get the transaction hash.
     *
     * @return Hash
     */
    public function getTransactionHash(): Hash
    {
        return $this->transactionHash;
    }

    /**
     * Accept a transaction hash.
     *
     * @param Hash $transactionHash
     * @return static
     */
    public function withTransactionHash(Hash $transactionHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->transactionHash = Copy::deep($transactionHash);

        return $clone;
    }

    /**
     * Get the result.
     *
     * @return TransactionResult
     */
    public function getResult(): TransactionResult
    {
        return $this->result;
    }

    /**
     * Accept a result.
     *
     * @param TransactionResult $result
     * @return static
     */
    public function withResult(TransactionResult $result): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->result = Copy::deep($result);

        return $clone;
    }
}
