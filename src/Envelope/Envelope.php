<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionSignaturePayload;
use StageRightLabs\Bloom\Transaction\TransactionV0;

interface Envelope
{
    /**
     * Return the underlying transaction.
     *
     * @return Transaction|TransactionV0|FeeBumpTransaction
     */
    public function getTransaction(): Transaction|TransactionV0|FeeBumpTransaction;

    /**
     * Get the signature list.
     *
     * @return DecoratedSignatureList
     */
    public function getSignatures(): DecoratedSignatureList;

    /**
     * Set the signature list.
     *
     * @param DecoratedSignatureList $signatures
     * @return static
     */
    public function withSignatures(DecoratedSignatureList $signatures): static;

    /**
     * Add a signature.
     *
     * @param DecoratedSignature $signature
     * @return static
     */
    public function addSignature(DecoratedSignature $signature): static;

    /**
     * Set up an empty signature list.
     *
     * @return static
     */
    public function withEmptySignatureList(): static;

    /**
     * Return a representation of the underlying transaction that, when hashed,
     * can be signed and turned into a decorated signature.
     *
     * @param string $network
     * @return TransactionSignaturePayload
     */
    public function getSignaturePayload(string $network): TransactionSignaturePayload;

    /**
     * Return a hash of the signature payload.
     *
     * @param string $network
     * @return Hash
     */
    public function getHash(string $network): Hash;
}
