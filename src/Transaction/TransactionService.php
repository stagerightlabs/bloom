<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use DateTime;
use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Keypair\Keypair;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Service;

final class TransactionService extends Service
{
    /**
     * Create a new transaction
     *
     * @param Addressable|string $sourceAddress
     * @param Int64|string|int $sequenceNumber
     * @param UInt32|int $fee
     * @throws \StageRightLabs\Bloom\Exception\MathException
     * @throws \StageRightLabs\Bloom\Exception\InvalidArgumentException
     * @return Transaction
     */
    public function create(Addressable|string $sourceAddress, Int64|string|int $sequenceNumber, UInt32|int $fee = null): Transaction
    {
        // Prepare
        if ($sourceAddress instanceof Addressable) {
            $sourceAddress = $sourceAddress->getAddress();
        }
        $keypair = new Keypair($sourceAddress);
        $account = (new Account())->withKeyPair($keypair);

        // Sequence Number
        $sequenceNumber = SequenceNumber::of($sequenceNumber);

        // Use the minimum fee amount if none was provided.
        if (!$fee) {
            $fee = UInt32::of(Bloom::MINIMUM_OPERATION_FEE);
        }

        // Create the transaction
        $transaction = Transaction::for(
            $account,
            $sequenceNumber,
            $fee,
        )->withPreconditions(Preconditions::default());

        return $transaction;
    }

    /**
     * Create a new fee bump transaction.
     *
     * @see https://developers.stellar.org/docs/glossary/fee-bumps/#replace-by-fee
     * @param TransactionEnvelope $envelope
     * @param Int64|int $fee
     * @param AccountId|Addressable|string $feeSource
     * @return FeeBumpTransaction
     * @throws \StageRightLabs\Bloom\Exception\InvalidArgumentException
     */
    public function createFeeBumpTransaction(TransactionEnvelope $envelope, Int64|int $fee, AccountId|Addressable|string $feeSource): FeeBumpTransaction
    {
        return FeeBumpTransaction::for($envelope, $fee, $feeSource);
    }

    /**
     * Add an operation to a transaction.
     *
     * @param Transaction $transaction
     * @param Operation $operation
     * @return Transaction
     */
    public function addOperation(Transaction $transaction, Operation $operation): Transaction
    {
        return $transaction->withOperation($operation);
    }

    /**
     * Add a minimum time precondition to a transaction.
     *
     * An integer value will be interpreted as a Unix epoch.
     *
     * @param Transaction $transaction
     * @param TimePoint|DateTime|string|int $minTime
     * @return Transaction
     */
    public function addMinimumTimePrecondition(Transaction $transaction, TimePoint|DateTime|string|int $minTime): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMinimumTimePoint($minTime)
            )
        );
    }

    /**
     * Add a maximum time precondition to a transaction.
     *
     * An integer value will be interpreted as a Unix epoch.
     *
     * @param Transaction $transaction
     * @param TimePoint|DateTime|string|int $maxTime
     * @return Transaction
     */
    public function addMaximumTimePrecondition(Transaction $transaction, TimePoint|DateTime|string|int $maxTime): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMaximumTimePoint($maxTime)
            )
        );
    }

    /**
     * Set the transaction to expire after a specified number of seconds.
     *
     * @param Transaction $transaction
     * @param int $seconds
     * @return Transaction
     */
    public function setTimeout(Transaction $transaction, int $seconds): Transaction
    {
        // Set the maximum time bound to be $seconds in the future
        $transaction = $this->addMaximumTimePrecondition(
            $transaction,
            TimePoint::fromNativeString("+{$seconds} seconds")
        );

        // Set the minimum time bound to the current time.
        return $this->addMinimumTimePrecondition($transaction, new \DateTime());
    }

    /**
     * Add a minimum ledger offset as a precondition. The transaction will
     * only be valid after this many ledgers have closed.
     *
     * @param Transaction $transaction
     * @param UInt32|int $minLedgerOffset
     * @return Transaction
     */
    public function addMinimumLedgerOffsetPrecondition(Transaction $transaction, UInt32|int $minLedgerOffset): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMinimumLedgerOffset($minLedgerOffset)
            )
        );
    }

    /**
     * Add a maximum ledger offset as a precondition. The transaction will
     * only be valid until this many ledgers have closed. If set to
     * zero only the minimum ledger offset will be considered.
     *
     * @param Transaction $transaction
     * @param UInt32|int $maxLedgerOffset
     * @return Transaction
     */
    public function addMaximumLedgerOffsetPrecondition(Transaction $transaction, UInt32|int $maxLedgerOffset): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMaximumLedgerOffset($maxLedgerOffset)
            )
        );
    }

    /**
     * Add a minimum source account sequence number precondition. Defines
     * what sequence number the source account must reach before a
     * transaction becomes valid.
     *
     * @param Transaction $transaction
     * @param SequenceNumber|OptionalSequenceNumber $minimumSequenceNumber
     * @return Transaction
     */
    public function addMinimumSequenceNumberPrecondition(Transaction $transaction, SequenceNumber|OptionalSequenceNumber $minimumSequenceNumber): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMinimumSequenceNumber($minimumSequenceNumber)
            )
        );
    }

    /**
     * Add a minium sequence age precondition. This defines the how much older
     * the current ledger's sequence time must be than the source account's
     * sequence time for a transaction to be valid.
     *
     * @param Transaction $transaction
     * @param Duration $minimumSequenceAge
     * @return Transaction
     */
    public function addMinimumSequenceAgePrecondition(Transaction $transaction, Duration $minimumSequenceAge): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMinimumSequenceAge($minimumSequenceAge)
            )
        );
    }

    /**
     * Add a minimum sequence ledger gap precondition. This defines how much
     * greater the current ledger number must be than the source account's
     * sequence ledger number for a transaction to be valid.
     *
     * @param Transaction $transaction
     * @param UInt32|int $minimumSequenceLedgerGap
     * @return Transaction
     */
    public function addMinimumSequenceLedgerGapPrecondition(Transaction $transaction, UInt32|int $minimumSequenceLedgerGap): Transaction
    {
        return $transaction->withPreconditions(
            Preconditions::wrapPreconditionsV2(
                $this->fetchPreconditions($transaction)->withMinimumSequenceLedgerGap($minimumSequenceLedgerGap)
            )
        );
    }

    /**
     * Remove existing preconditions from a transaction.
     *
     * @param Transaction $transaction
     * @return Transaction
     */
    public function removePreconditions(Transaction $transaction): Transaction
    {
        return $transaction->withPreconditions(Preconditions::none());
    }

    /**
     * Fetch existing preconditions from a transaction and cast to PreconditionsV2 format.
     *
     * @param Transaction $transaction
     * @return PreconditionsV2
     */
    protected function fetchPreconditions(Transaction $transaction): PreconditionsV2
    {
        $preconditions = $transaction->getPreconditions()?->unwrap();

        // The preconditions might be null or possibly XDR::VOID
        if (is_string($preconditions) || is_null($preconditions)) {
            return new PreconditionsV2();
        }

        if ($preconditions instanceof TimeBounds) {
            return (new PreconditionsV2())->withTimeBounds($preconditions);
        }

        return $preconditions;
    }
}
