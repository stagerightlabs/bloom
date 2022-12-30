<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\OperationList;
use StageRightLabs\Bloom\Operation\OperationVariety;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionV0 implements XdrStruct
{
    /**
     * Properties
     */
    protected ED25519 $sourceAccountEd25519;
    protected UInt32 $fee;
    protected SequenceNumber $sequenceNumber;
    protected OptionalTimeBounds $optionalTimeBounds;
    protected Memo $memo;
    protected OperationList $operations;
    protected TransactionV0Ext $ext;

    /**
     * Create a new transaction for the provided source account.
     *
     * @param Addressable $account
     * @param SequenceNumber $sequenceNumber
     * @param ?UInt32 $fee
     * @return static
     */
    public static function for(Addressable $account, SequenceNumber $sequenceNumber, UInt32 $fee = null): static
    {
        // Establish a default fee amount if none is provided
        if (!$fee) {
            $fee = UInt32::of(Bloom::MINIMUM_OPERATION_FEE);
        }

        // Create the new transaction
        $transaction = new static();

        $transaction->sourceAccountEd25519 = ED25519::fromAddress($account);
        $transaction->fee = $fee;
        $transaction->sequenceNumber = $sequenceNumber;
        $transaction->optionalTimeBounds = OptionalTimeBounds::some(TimeBounds::oneYear());
        $transaction->memo = Memo::none();
        $transaction->operations = OperationList::empty();
        $transaction->ext = TransactionV0Ext::empty();

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
        if (!isset($this->sourceAccountEd25519)) {
            throw new InvalidArgumentException('The transaction is missing a source account ED25519 value');
        }

        if (!isset($this->fee)) {
            throw new InvalidArgumentException('The transaction is missing a fee');
        }

        if (!isset($this->sequenceNumber)) {
            throw new InvalidArgumentException('The transaction is missing a sequence number');
        }

        if (!isset($this->optionalTimeBounds)) {
            throw new InvalidArgumentException('The transaction is missing time bounds. Use a null optional time bounds to indicate the transaction has no time limitations');
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

        $xdr->write($this->sourceAccountEd25519)
            ->write($this->fee)
            ->write($this->sequenceNumber)
            ->write($this->optionalTimeBounds)
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

        $transaction->sourceAccountEd25519 = $xdr->read(ED25519::class);
        $transaction->fee = $xdr->read(UInt32::class);
        $transaction->sequenceNumber = $xdr->read(SequenceNumber::class);
        $transaction->optionalTimeBounds = $xdr->read(OptionalTimeBounds::class);
        $transaction->ext = $xdr->read(TransactionV0Ext::class);

        return $transaction;
    }

    /**
     * Get the source account ED25519.
     *
     * @return ED25519
     */
    public function getSourceAccountEd25519(): ED25519
    {
        return $this->sourceAccountEd25519;
    }

    /**
     * Set the source account ED25519.
     *
     * @param ED25519 $sourceAccountEd25519
     * @return static
     */
    public function withSourceAccountEd25519(ED25519 $sourceAccountEd25519): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sourceAccountEd25519 = Copy::deep($sourceAccountEd25519);

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
     * Set the sequence number.
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
     * Does this transaction have set time bounds?
     *
     * @return bool
     */
    public function hasTimeBounds(): bool
    {
        return $this->optionalTimeBounds->hasValue();
    }

    /**
     * Get the time bounds.
     *
     * @return TimeBounds|null
     */
    public function getTimeBounds(): ?TimeBounds
    {
        return $this->optionalTimeBounds->unwrap();
    }

    /**
     * Set the time bounds.
     *
     * @param OptionalTimeBounds|TimeBounds|null $timeBounds
     * @return static
     */
    public function withTimeBounds(OptionalTimeBounds|TimeBounds|null $timeBounds): static
    {
        /** @var static */
        $clone = Copy::deep($this);

        if (is_null($timeBounds)) {
            $timeBounds = OptionalTimeBounds::none();
        }

        if ($timeBounds instanceof OptionalTimeBounds) {
            $clone->optionalTimeBounds = Copy::deep($timeBounds);
        } else {
            $clone->optionalTimeBounds = OptionalTimeBounds::some($timeBounds);
        }

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

        return $clone;
    }

    /**
     * Get the value of ext.
     *
     * @return TransactionV0Ext
     */
    public function getExtension(): TransactionV0Ext
    {
        return $this->ext;
    }

    /**
     * Set the value of ext.
     *
     * @param TransactionV0Ext $ext
     *
     * @return static
     */
    public function withExtension(TransactionV0Ext $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
