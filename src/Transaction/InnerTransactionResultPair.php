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

final class InnerTransactionResultPair implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $transactionHash;
    protected InnerTransactionResult $result;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->transactionHash)) {
            throw new InvalidArgumentException('The inner transaction result pair is missing a transaction hash');
        }

        if (!isset($this->result)) {
            throw new InvalidArgumentException('The inner transaction result pair is missing an inner transaction result');
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
        $innerTransactionResultPair = new static();
        $innerTransactionResultPair->transactionHash = $xdr->read(Hash::class);
        $innerTransactionResultPair->result = $xdr->read(InnerTransactionResult::class);

        return $innerTransactionResultPair;
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
     * Get the inner transaction result.
     *
     * @return InnerTransactionResult
     */
    public function getInnerTransactionResult(): InnerTransactionResult
    {
        return $this->result;
    }

    /**
     * Accept an inner transaction result.
     *
     * @param InnerTransactionResult $result
     * @return static
     */
    public function withInnerTransactionResult(InnerTransactionResult $result): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->result = Copy::deep($result);

        return $clone;
    }
}
