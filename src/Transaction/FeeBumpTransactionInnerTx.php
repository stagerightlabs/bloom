<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

class FeeBumpTransactionInnerTx extends Union implements XdrUnion
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
            EnvelopeType::ENVELOPE_TRANSACTION => TransactionV1Envelope::class,
        ];
    }

    /**
     * Create a new v1 transaction instance.
     *
     * @return static
     */
    public static function wrapTransactionV1Envelope(TransactionV1Envelope $envelope): static
    {
        $feeBumpTransactionInnerTx = new static();
        $feeBumpTransactionInnerTx->discriminator = EnvelopeType::transaction();
        $feeBumpTransactionInnerTx->value = $envelope;

        return $feeBumpTransactionInnerTx;
    }

    /**
     * Return the underlying transaction envelope.
     *
     * @return TransactionV1Envelope|null
     */
    public function unwrap(): ?TransactionV1Envelope
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
