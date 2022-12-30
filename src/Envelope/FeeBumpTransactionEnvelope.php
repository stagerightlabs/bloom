<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayload;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayloadTaggedTransaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class FeeBumpTransactionEnvelope implements Envelope, XdrStruct
{
    /**
     * Properties
     */
    protected FeeBumpTransaction $transaction;
    protected DecoratedSignatureList $signatures;

    /**
     * Write this struct to XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->transaction)) {
            throw new InvalidArgumentException('The fee bump transaction envelope is missing a transaction');
        }

        if (!isset($this->signatures)) {
            throw new InvalidArgumentException('The fee bump transaction envelope is missing a signature list');
        }

        $xdr->write($this->transaction)->write($this->signatures);
    }

    /**
     * Read the operation from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $envelope = new static();
        $envelope->transaction = $xdr->read(FeeBumpTransaction::class);
        $envelope->signatures = $xdr->read(DecoratedSignatureList::class);

        return $envelope;
    }

    /**
     * Return the underlying transaction.
     *
     * @return Transaction|TransactionV0|FeeBumpTransaction
     */
    public function getTransaction(): Transaction|TransactionV0|FeeBumpTransaction
    {
        return $this->transaction;
    }

    /**
     * Set the fee bump transaction.
     *
     * @return static
     */
    public function withTransaction(FeeBumpTransaction $transaction): static
    {
        $clone = Copy::deep($this);
        $clone->transaction = Copy::deep($transaction);

        return $clone;
    }

    /**
     * Get the signatures.
     *
     * @return DecoratedSignatureList
     */
    public function getSignatures(): DecoratedSignatureList
    {
        return $this->signatures;
    }

    /**
     * Set the signatures.
     *
     * @return static
     */
    public function withSignatures(DecoratedSignatureList $signatures): static
    {
        $clone = Copy::deep($this);
        $clone->signatures = Copy::deep($signatures);

        return $clone;
    }

    /**
     * Add a signature.
     *
     * @param DecoratedSignature $signature
     * @return static
     */
    public function addSignature(DecoratedSignature $signature): static
    {
        return $this->withSignatures(
            $this->signatures->push(Copy::deep($signature))
        );
    }

    /**
     * Set up an empty signature list.
     *
     * @return static
     */
    public function withEmptySignatureList(): static
    {
        $clone = Copy::deep($this);
        $clone->signatures = DecoratedSignatureList::empty();

        return $clone;
    }

    /**
     * Return a representation of the underlying transaction that, when hashed,
     * can be signed and turned into a decorated signature.
     *
     * @param string $network
     * @return TransactionSignaturePayload
     */
    public function getSignaturePayload(string $network): TransactionSignaturePayload
    {
        return TransactionSignaturePayload::for(
            TransactionSignaturePayloadTaggedTransaction::wrapFeeBumpTransaction($this->transaction),
            $network
        );
    }

    /**
     * Return a hash of the signature payload.
     *
     * @param string $network
     * @return Hash
     */
    public function getHash(string $network): Hash
    {
        return Hash::make(
            XDR::fresh()->write($this->getSignaturePayload($network))->buffer()
        );
    }
}
