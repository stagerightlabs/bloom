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

final class TransactionSignaturePayload implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $networkId;
    protected TransactionSignaturePayloadTaggedTransaction $taggedTransaction;

    /**
     * Create a new instance from a tagged transaction and a network ID hash.
     *
     * @param TransactionSignaturePayloadTaggedTransaction $taggedTransaction
     * @param Hash $networkId
     * @return static
     */
    public static function for(
        TransactionSignaturePayloadTaggedTransaction $taggedTransaction,
        Hash|string $networkId,
    ): static {
        $transactionSignaturePayload = new static();
        $transactionSignaturePayload->taggedTransaction = Copy::deep($taggedTransaction);
        $transactionSignaturePayload->networkId = is_string($networkId)
            ? Hash::make($networkId)
            : Copy::deep($networkId);

        return $transactionSignaturePayload;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->networkId)) {
            throw new InvalidArgumentException('The transaction signature payload is missing a network ID hash');
        }

        if (!isset($this->taggedTransaction)) {
            throw new InvalidArgumentException('The transaction signature payload is missing a tagged transaction');
        }

        $xdr->write($this->networkId, Hash::class)
            ->write($this->taggedTransaction, TransactionSignaturePayloadTaggedTransaction::class);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionSignaturePayload = new static();
        $transactionSignaturePayload->networkId = $xdr->read(Hash::class);
        $transactionSignaturePayload->taggedTransaction = $xdr->read(TransactionSignaturePayloadTaggedTransaction::class);

        return $transactionSignaturePayload;
    }

    /**
     * Get the network ID.
     *
     * @return Hash
     */
    public function getNetworkId(): Hash
    {
        return $this->networkId;
    }

    /**
     * Set the network ID.
     *
     * @param Hash|string $networkId
     * @return static
     */
    public function withNetworkId(Hash|string $networkId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->networkId = is_string($networkId)
            ? Hash::make($networkId)
            : Copy::deep($networkId);

        return $clone;
    }

    /**
     * Get the tagged transaction.
     *
     * @return TransactionSignaturePayloadTaggedTransaction
     */
    public function getTaggedTransaction(): TransactionSignaturePayloadTaggedTransaction
    {
        return $this->taggedTransaction;
    }

    /**
     * Set the tagged transaction.
     *
     * @param TransactionSignaturePayloadTaggedTransaction $taggedTransaction
     * @return static
     */
    public function withTaggedTransaction(TransactionSignaturePayloadTaggedTransaction $taggedTransaction): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->taggedTransaction = Copy::deep($taggedTransaction);

        return $clone;
    }
}
