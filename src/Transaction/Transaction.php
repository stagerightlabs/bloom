<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\OperationList;
use StageRightLabs\Bloom\Operation\OperationVariety;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Transaction implements XdrStruct
{
    /**
     * Properties
     */
    protected AccountId $sourceAccount;
    protected UInt32 $fee;
    protected SequenceNumber $sequenceNumber;
    protected Preconditions $preconditions;
    protected Memo $memo;
    protected OperationList $operations;
    protected TransactionExt $ext;

    /**
     * Create a new transaction for the provided source account.
     *
     * @param AccountId|Addressable $account
     * @param SequenceNumber $sequenceNumber
     * @param UInt32|int|null $fee
     * @return static
     */
    public static function for(AccountId|Addressable $account, SequenceNumber $sequenceNumber, UInt32|int|null $fee = null): static
    {
        // Establish a default fee amount if none is provided
        $fee = is_null($fee)
            ? UInt32::of(Bloom::MINIMUM_OPERATION_FEE)
            : UInt32::of($fee);

        // Create the new transaction
        $transaction = new static();

        // The source account will be encoded as an XDR 'MuxedAccount' object.
        $transaction->sourceAccount = $account instanceof AccountId
            ? $account
            : AccountId::fromAddressable($account->getAddress());

        $transaction->fee = $fee;
        $transaction->sequenceNumber = $sequenceNumber;
        $transaction->preconditions = Preconditions::none();
        $transaction->memo = Memo::none();
        $transaction->operations = OperationList::empty();
        $transaction->ext = TransactionExt::empty();

        return $transaction;
    }

    /**
     * Return the maximum threshold category for the operations in the transaction.
     *
     * @return string|null
     */
    public function getOperationThreshold(): ?string
    {
        if (!isset($this->operations) || $this->operations->isEmpty()) {
            return null;
        }

        $thresholds = array_filter(array_map(
            function ($operation) {
                if ($operation->getBody()->unwrap() instanceof OperationVariety) {
                    return $operation->getBody()->unwrap()->getThreshold();
                }
                if ($operation->getBody()->unwrap() instanceof MuxedAccount) {
                    // This is an AccountMerge operation
                    return Thresholds::CATEGORY_HIGH;
                }
            },
            $this->operations->toArray(),
        ));

        if (in_array(Thresholds::CATEGORY_HIGH, $thresholds, true)) {
            return Thresholds::CATEGORY_HIGH;
        }

        if (in_array(Thresholds::CATEGORY_MEDIUM, $thresholds, true)) {
            return Thresholds::CATEGORY_MEDIUM;
        }

        return Thresholds::CATEGORY_LOW;
    }

    /**
     * Encode the transaction as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sourceAccount)) {
            throw new InvalidArgumentException('The transaction is missing a source account');
        }

        if (!isset($this->fee)) {
            throw new InvalidArgumentException('The transaction is missing a fee');
        }

        if (!isset($this->sequenceNumber)) {
            throw new InvalidArgumentException('The transaction is missing a sequence number');
        }

        if (!isset($this->preconditions)) {
            throw new InvalidArgumentException('The transaction is missing preconditions. Use a \'none\' precondition to indicate the transaction has no preconditions.');
        }

        if (!isset($this->memo)) {
            throw new InvalidArgumentException('The transaction is missing a memo. Use a null memo to indicate the transaction has no memo.');
        }

        if (!isset($this->operations)) {
            throw new InvalidArgumentException('The transaction is missing an operations list.');
        }

        if (!isset($this->ext)) {
            throw new InvalidArgumentException('The transaction is missing an ext value');
        }

        $xdr->write($this->sourceAccount)
            ->write($this->fee)
            ->write($this->sequenceNumber)
            ->write($this->preconditions)
            ->write($this->memo)
            ->write($this->operations)
            ->write($this->ext);
    }

    /**
     * Read the transaction from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transaction = new static();

        $transaction->sourceAccount = $xdr->read(AccountId::class);
        $transaction->fee = $xdr->read(UInt32::class);
        $transaction->sequenceNumber = $xdr->read(SequenceNumber::class);
        $transaction->preconditions = $xdr->read(Preconditions::class);
        $transaction->memo = $xdr->read(Memo::class);
        $transaction->operations = $xdr->read(OperationList::class);
        $transaction->ext = $xdr->read(TransactionExt::class);

        return $transaction;
    }

    /**
     * Get the source account.
     *
     * @return AccountId
     */
    public function getSourceAccount(): AccountId
    {
        return $this->sourceAccount;
    }

    /**
     * Set the source account.
     *
     * @param AccountId|Addressable $sourceAccount
     * @return static
     */
    public function withSourceAccount(AccountId|Addressable $sourceAccount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sourceAccount = $sourceAccount instanceof AccountId
            ? Copy::deep($sourceAccount)
            : AccountId::fromAddressable($sourceAccount->getAddress());

        return $clone;
    }

    /**
     * Get the fee.
     *
     * @return UInt32
     */
    public function getFee(): UInt32
    {
        return $this->fee;
    }

    /**
     * Set the fee.
     *
     * @param UInt32 $fee
     * @return static
     */
    public function withFee(UInt32 $fee): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->fee = Copy::deep($fee);

        return $clone;
    }

    /**
     * Get the sequence number.
     *
     * @return SequenceNumber
     */
    public function getSequenceNumber(): SequenceNumber
    {
        return $this->sequenceNumber;
    }

    /**
     * Accept a sequence number.
     *
     * @param SequenceNumber $sequenceNumber
     * @return static
     */
    public function withSequenceNumber(SequenceNumber $sequenceNumber): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sequenceNumber = Copy::deep($sequenceNumber);

        return $clone;
    }

    /**
     * Get the preconditions.
     *
     * @return Preconditions|null
     */
    public function getPreconditions(): ?Preconditions
    {
        $possibleTimeBounds = $this->preconditions->unwrap();

        // The preconditions might be XDR::VOID
        if (is_string($possibleTimeBounds)) {
            return Preconditions::none();
        }

        // A transaction created prior to Protocol 19 might have time bounds
        if ($possibleTimeBounds instanceof TimeBounds) {
            return Preconditions::wrapTimeBounds($possibleTimeBounds);
        }

        return $this->preconditions;
    }

    /**
     * Accept a set of preconditions.
     *
     * @param Preconditions $preconditions
     * @return static
     */
    public function withPreconditions(Preconditions $preconditions): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->preconditions = $preconditions;

        return $clone;
    }

    /**
     * Get the memo.
     *
     * @return Memo
     */
    public function getMemo(): Memo
    {
        return $this->memo;
    }

    /**
     * Set the memo.
     *
     * @param Memo $memo
     * @return static
     */
    public function withMemo(Memo $memo): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->memo = Copy::deep($memo);

        return $clone;
    }

    /**
     * Get the operations list.
     *
     * @return OperationList
     */
    public function getOperationList(): OperationList
    {
        return $this->operations;
    }

    /**
     * Return the number of operations
     *
     * @return int
     */
    public function getOperationCount(): int
    {
        return $this->operations->count();
    }

    /**
     * Set the operations list.
     *
     * @param OperationList $operations
     * @return static
     */
    public function withOperationList(OperationList $operations): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->operations = Copy::deep($operations);

        return $clone;
    }

    /**
     * Add an operation to the operation list.
     *
     * @param Operation $operation
     * @return static
     */
    public function withOperation(Operation $operation): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->operations = $clone->operations->push(Copy::deep($operation));

        // Do we need to increase the fee amount?
        if (!isset($clone->fee) || $clone->fee->toNativeInt() < $clone->operations->count() * Bloom::MINIMUM_OPERATION_FEE) {
            $clone->fee = UInt32::of($clone->operations->count() * Bloom::MINIMUM_OPERATION_FEE);
        }

        return $clone;
    }

    /**
     * Get the value of ext.
     *
     * @return TransactionExt
     */
    public function getExtension(): TransactionExt
    {
        return $this->ext;
    }

    /**
     * Set the value of ext.
     *
     * @param TransactionExt $ext
     * @return static
     */
    public function withExtension(TransactionExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
