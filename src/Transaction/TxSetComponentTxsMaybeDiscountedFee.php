<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Envelope\TransactionEnvelopeList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\OptionalInt64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TxSetComponentTxsMaybeDiscountedFee implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected OptionalInt64 $baseFee;
    protected TransactionEnvelopeList $transactions;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->baseFee)) {
            $this->baseFee = OptionalInt64::none();
        }

        if (!isset($this->transactions)) {
            $this->transactions = TransactionEnvelopeList::empty();
        }

        $xdr->write($this->baseFee)
            ->write($this->transactions);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $txSetComponentType = new static();
        $txSetComponentType->baseFee = $xdr->read(OptionalInt64::class);
        $txSetComponentType->transactions = $xdr->read(TransactionEnvelopeList::class);

        return $txSetComponentType;
    }

    /**
     * Get the base fee.
     *
     * @return Int64|null
     */
    public function getBaseFee(): ?Int64
    {
        return isset($this->baseFee)
            ? $this->baseFee->unwrap()
            : null;
    }

    /**
     * Accept an amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param OptionalInt64|Int64|ScaledAmount|int|string $baseFee
     * @return static
     */
    public function withBaseFee(OptionalInt64|Int64|ScaledAmount|int|string $baseFee): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->baseFee = $baseFee instanceof OptionalInt64
            ? Copy::deep($baseFee)
            : OptionalInt64::some(Int64::normalize($baseFee));

        return $clone;
    }

    /**
     * Get the transactions.
     *
     * @return TransactionEnvelopeList
     */
    public function getTransactions(): TransactionEnvelopeList
    {
        return $this->transactions;
    }

    /**
     * Accept a list of transactions.
     *
     * @param TransactionEnvelopeList $transactions
     * @return static
     */
    public function withTransactions(TransactionEnvelopeList $transactions): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->transactions = Copy::deep($transactions);

        return $clone;
    }
}
