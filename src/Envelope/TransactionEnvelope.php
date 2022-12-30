<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\DecoratedSignatureList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

class TransactionEnvelope extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return EnvelopeType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            EnvelopeType::ENVELOPE_TRANSACTION_V0 => TransactionV0Envelope::class,
            EnvelopeType::ENVELOPE_TRANSACTION    => TransactionV1Envelope::class,
            EnvelopeType::ENVELOPE_FEE_BUMP       => FeeBumpTransactionEnvelope::class,
        ];
    }

    /**
     * Create a new transactionV0 envelope.
     *
     * @return static
     */
    public static function wrapTransactionV0Envelope(TransactionV0Envelope $envelope): static
    {
        $transactionEnvelope = new static();
        $transactionEnvelope->discriminator = EnvelopeType::transactionV0();
        $transactionEnvelope->value = $envelope;

        return $transactionEnvelope;
    }

    /**
     * Create a new transaction envelope.
     *
     * @return static
     */
    public static function wrapTransactionV1Envelope(TransactionV1Envelope $envelope): static
    {
        $transactionEnvelope = new static();
        $transactionEnvelope->discriminator = EnvelopeType::transaction();
        $transactionEnvelope->value = $envelope;

        return $transactionEnvelope;
    }

    /**
     * Create a new transaction fee bump envelope.
     *
     * @return static
     */
    public static function wrapFeeBumpTransactionEnvelope(FeeBumpTransactionEnvelope $envelope): static
    {
        $transactionEnvelope = new static();
        $transactionEnvelope->discriminator = EnvelopeType::feeBump();
        $transactionEnvelope->value = $envelope;

        return $transactionEnvelope;
    }

    /**
     * Enclose a transaction in an envelope.
     *
     * @param Transaction|TransactionV0|FeeBumpTransaction $transaction
     * @return static
     */
    public static function enclose(Transaction|TransactionV0|FeeBumpTransaction $transaction): static
    {
        if ($transaction instanceof Transaction) {
            return static::wrapTransactionV1Envelope(
                (new TransactionV1Envelope())->withTransaction($transaction)->withEmptySignatureList()
            );
        }

        if ($transaction instanceof TransactionV0) {
            return static::wrapTransactionV0Envelope(
                (new TransactionV0Envelope())->withTransaction($transaction)->withEmptySignatureList()
            );
        }

        if ($transaction instanceof FeeBumpTransaction) {
            return static::wrapFeeBumpTransactionEnvelope(
                (new FeeBumpTransactionEnvelope())->withTransaction($transaction)->withEmptySignatureList()
            );
        }
    }

    /**
     * Get the signatures.
     *
     * @return DecoratedSignatureList
     */
    public function getSignatures(): DecoratedSignatureList
    {
        if ($list = $this->unwrap()) {
            return $list->getSignatures();
        }

        return DecoratedSignatureList::empty();
    }

    /**
     * Add a signature.
     *
     * @param DecoratedSignature $signature
     * @throws InvalidArgumentException
     * @return static
     */
    public function addSignature(DecoratedSignature $signature): static
    {
        if (!$envelope = $this->unwrap()) {
            throw new InvalidArgumentException('Attempting to sign an invalid transaction envelope');
        }

        $envelope = $envelope->addSignature($signature);

        if ($envelope instanceof TransactionV0Envelope) {
            return self::wrapTransactionV0Envelope($envelope);
        }

        if ($envelope instanceof FeeBumpTransactionEnvelope) {
            return self::wrapFeeBumpTransactionEnvelope($envelope);
        }

        return self::wrapTransactionV1Envelope($envelope);
    }

    /**
     * Return the underlying transaction envelope.
     *
     * @return TransactionV0Envelope|TransactionV1Envelope|FeeBumpTransactionEnvelope|null
     */
    public function unwrap(): TransactionV0Envelope|TransactionV1Envelope|FeeBumpTransactionEnvelope|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the underlying transaction.
     *
     * @return Transaction|TransactionV0|FeeBumpTransaction|null
     */
    public function getTransaction(): Transaction|TransactionV0|FeeBumpTransaction|null
    {
        if ($envelope = $this->unwrap()) {
            return $envelope->getTransaction();
        }

        return null;
    }

    /**
     * Return a hash of the transaction signature payload.
     *
     * @param string $network
     * @return Hash|null
     */
    public function getHash(string $network): ?Hash
    {
        if ($envelope = $this->unwrap()) {
            return $envelope->getHash($network);
        }

        return null;
    }
}
