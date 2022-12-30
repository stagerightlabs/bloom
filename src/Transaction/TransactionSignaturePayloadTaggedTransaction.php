<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Envelope\EnvelopeType;
use StageRightLabs\Bloom\Primitives\Union;

class TransactionSignaturePayloadTaggedTransaction extends Union
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
            EnvelopeType::ENVELOPE_TRANSACTION => Transaction::class,
            EnvelopeType::ENVELOPE_FEE_BUMP    => FeeBumpTransaction::class,
        ];
    }

    /**
     * Create a new instance from a standard Transaction.
     *
     * For the sake of backward compatibility a V0 transactions will be
     * signed as if they were V1 transactions. We will convert the
     * V0 source account ED25519 into a muxed account object.
     *
     * @param Transaction|TransactionV0 $transaction
     * @return static
     */
    public static function wrapTransaction(Transaction|TransactionV0 $transaction): static
    {
        if ($transaction instanceof TransactionV0) {
            $timeBounds = $transaction->getTimeBounds();
            $transaction = (new Transaction())
                ->withSourceAccount(AccountId::fromAddressable($transaction->getSourceAccountEd25519()->getAddress()))
                ->withFee($transaction->getFee())
                ->withMemo($transaction->getMemo())
                ->withOperationList($transaction->getOperationList())
                ->withSequenceNumber($transaction->getSequenceNumber())
                ->withPreconditions(Preconditions::none())
                ->withExtension(TransactionExt::empty());

            if ($timeBounds) {
                $transaction = $transaction->withPreconditions(Preconditions::wrapTimeBounds($timeBounds));
            }
        }

        $transactionSignaturePayloadTaggedTransaction = new static();
        $transactionSignaturePayloadTaggedTransaction->discriminator = EnvelopeType::transaction();
        $transactionSignaturePayloadTaggedTransaction->value = $transaction;

        return $transactionSignaturePayloadTaggedTransaction;
    }

    /**
     * Create a new instance from a fee bump transaction.
     *
     * @param FeeBumpTransaction $transaction
     * @return static
     */
    public static function wrapFeeBumpTransaction(FeeBumpTransaction $transaction): static
    {
        $transactionSignaturePayloadTaggedTransaction = new static();
        $transactionSignaturePayloadTaggedTransaction->discriminator = EnvelopeType::feeBump();
        $transactionSignaturePayloadTaggedTransaction->value = $transaction;

        return $transactionSignaturePayloadTaggedTransaction;
    }

    /**
     * Return the transaction.
     *
     * @return Transaction|FeeBumpTransaction|null
     */
    public function unwrap(): Transaction|FeeBumpTransaction|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
