<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionResult implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Int64 $feeCharged;
    protected TransactionResultResult $result;
    protected TransactionResultExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->feeCharged)) {
            throw new InvalidArgumentException('The transaction result is missing a fee charged');
        }

        if (!isset($this->result)) {
            throw new InvalidArgumentException('The transaction result is missing a TransactionResultResult');
        }

        if (!isset($this->ext)) {
            $this->ext = TransactionResultExt::empty();
        }

        $xdr->write($this->feeCharged)
            ->write($this->result)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionResult = new static();
        $transactionResult->feeCharged = $xdr->read(Int64::class);
        $transactionResult->result = $xdr->read(TransactionResultResult::class);
        $transactionResult->ext = $xdr->read(TransactionResultExt::class);

        return $transactionResult;
    }

    /**
     * Get the fee charged.
     *
     * @return Int64
     */
    public function getFeeCharged(): Int64
    {
        return $this->feeCharged;
    }

    /**
     * Accept a fee charged.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $feeCharged
     * @return static
     */
    public function withFeeCharged(Int64|ScaledAmount|int|string $feeCharged): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->feeCharged = Int64::normalize($feeCharged);

        return $clone;
    }

    /**
     * Get the result.
     *
     * @return TransactionResultResult
     */
    public function getResult(): TransactionResultResult
    {
        return $this->result;
    }

    /**
     * Accept a result.
     *
     * @param TransactionResultResult $result
     * @return static
     */
    public function withResult(TransactionResultResult $result): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->result = Copy::deep($result);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TransactionResultExt
     */
    public function getExtension(): TransactionResultExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TransactionResultExt $ext
     * @return static
     */
    public function withExtension(TransactionResultExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }

    /**
     * Retrieve a list of operation results.
     *
     * @return OperationResultList
     */
    public function getOperationResultList(): OperationResultList
    {
        if (isset($this->result) && $this->result->unwrap() instanceof OperationResultList) {
            return $this->result->unwrap();
        }

        return OperationResultList::empty();
    }

    /**
     * Retrieve the inner transaction result pair if it is present in this result.
     *
     * @return InnerTransactionResultPair|null
     */
    public function getInnerTransactionResultPair(): ?InnerTransactionResultPair
    {
        if (isset($this->result) && $this->result->unwrap() instanceof InnerTransactionResultPair) {
            return $this->result->unwrap();
        }

        return null;
    }

    /**
     * Was the transaction successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        if (isset($this->result)) {
            return $this->result->wasSuccessful();
        }

        return false;
    }

    /**
     * Was the operation not successful?
     *
     * @return bool
     */
    public function wasNotSuccessful(): bool
    {
        return !$this->wasSuccessful();
    }

    /**
     * Return an error message that describes the problem if there was one.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        if (isset($this->result)) {
            return $this->result->getErrorMessage();
        }

        return null;
    }

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->result)) {
            return $this->result->getErrorCode();
        }

        return null;
    }
}
