<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Operation\OperationOutcome;
use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TransactionResultResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return TransactionResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            TransactionResultCode::FEE_BUMP_INNER_SUCCESS => InnerTransactionResultPair::class,
            TransactionResultCode::SUCCESS                => OperationResultList::class,
            TransactionResultCode::FAILED                 => OperationResultList::class,
            TransactionResultCode::TOO_EARLY              => XDR::VOID,
            TransactionResultCode::TOO_LATE               => XDR::VOID,
            TransactionResultCode::MISSING_OPERATION      => XDR::VOID,
            TransactionResultCode::BAD_SEQ                => XDR::VOID,
            TransactionResultCode::BAD_AUTH               => XDR::VOID,
            TransactionResultCode::INSUFFICIENT_BALANCE   => XDR::VOID,
            TransactionResultCode::NO_ACCOUNT             => XDR::VOID,
            TransactionResultCode::INSUFFICIENT_FEE       => XDR::VOID,
            TransactionResultCode::BAD_AUTH_EXTRA         => XDR::VOID,
            TransactionResultCode::INTERNAL_ERROR         => XDR::VOID,
            TransactionResultCode::NOT_SUPPORTED          => XDR::VOID,
            TransactionResultCode::FEE_BUMP_INNER_FAILED  => InnerTransactionResultPair::class,
            TransactionResultCode::BAD_SPONSORSHIP        => XDR::VOID,
            TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP => XDR::VOID,
            TransactionResultCode::MALFORMED              => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping a successful inner transaction result pair.
     *
     * @param InnerTransactionResultPair $innerTransactionResultPair
     * @return static
     */
    public static function wrapSuccessfulInnerTransactionResultPair(InnerTransactionResultPair $innerTransactionResultPair): static
    {
        $transactionResultResult = new static();
        $transactionResultResult->discriminator = TransactionResultCode::feeBumpInnerSuccess();
        $transactionResultResult->value = $innerTransactionResultPair;

        return $transactionResultResult;
    }

    /**
     * Create a new instance by wrapping a failed inner transaction result pair.
     *
     * @param InnerTransactionResultPair $innerTransactionResultPair
     * @return static
     */
    public static function wrapFailedInnerTransactionResultPair(InnerTransactionResultPair $innerTransactionResultPair): static
    {
        $transactionResultResult = new static();
        $transactionResultResult->discriminator = TransactionResultCode::feeBumpInnerFailed();
        $transactionResultResult->value = $innerTransactionResultPair;

        return $transactionResultResult;
    }

    /**
     * Create a new instance by wrapping a successful operation result list.
     *
     * @param OperationResultList $operationResultList
     * @return static
     */
    public static function wrapSuccessfulOperationResultList(OperationResultList $operationResultList): static
    {
        $transactionResultResult = new static();
        $transactionResultResult->discriminator = TransactionResultCode::success();
        $transactionResultResult->value = $operationResultList;

        return $transactionResultResult;
    }

    /**
     * Create a new instance by wrapping a failed operation result list.
     *
     * @param OperationResultList $operationResultList
     * @return static
     */
    public static function wrapFailedOperationResultList(OperationResultList $operationResultList): static
    {
        $transactionResultResult = new static();
        $transactionResultResult->discriminator = TransactionResultCode::failed();
        $transactionResultResult->value = $operationResultList;

        return $transactionResultResult;
    }

    /**
     * Return the underlying result value, if present.
     *
     * @return InnerTransactionResultPair|OperationResultList|null
     */
    public function unwrap(): InnerTransactionResultPair|OperationResultList|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the transaction result code.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof TransactionResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param TransactionResultCode $discriminator
     * @param OperationResultList|InnerTransactionResultPair|null $value
     * @return static
     */
    public static function simulate(TransactionResultCode $discriminator, OperationResultList|InnerTransactionResultPair $value = null): static
    {
        $transactionResultResult = new static();
        $transactionResultResult->discriminator = $discriminator;
        $transactionResultResult->value = $value;

        return $transactionResultResult;
    }

    /**
     * Was the transaction successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof TransactionResultCode
            && ($this->discriminator->getType() == TransactionResultCode::SUCCESS || $this->discriminator->getType() == TransactionResultCode::FEE_BUMP_INNER_SUCCESS);
    }

    /**
     * Was the transaction not successful?
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
        if (isset($this->discriminator) && $this->discriminator instanceof TransactionResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#end-sponsoring-future-reserves
     * @var array<string, string>
     */
    protected $messages = [
        TransactionResultCode::FEE_BUMP_INNER_SUCCESS => "The operation was successful",
        TransactionResultCode::SUCCESS                => "The operation was successful",
        TransactionResultCode::FAILED                 => "One of the operations failed (see List of Operations for errors)",
        TransactionResultCode::TOO_EARLY              => "Ledger closeTime before minTime value in the transaction",
        TransactionResultCode::TOO_LATE               => "Ledger closeTime after maxTime value in the transaction",
        TransactionResultCode::MISSING_OPERATION      => "No operation was specified",
        TransactionResultCode::BAD_SEQ                => "Sequence number does not match source account",
        TransactionResultCode::BAD_AUTH               => "Too few valid signatures / wrong network",
        TransactionResultCode::INSUFFICIENT_BALANCE   => "Fee would bring account below minimum balance",
        TransactionResultCode::NO_ACCOUNT             => "Source account not found",
        TransactionResultCode::INSUFFICIENT_FEE       => "Fee is too small",
        TransactionResultCode::BAD_AUTH_EXTRA         => "Unused signatures attached to transaction",
        TransactionResultCode::INTERNAL_ERROR         => "An unknown error occurred",
        TransactionResultCode::NOT_SUPPORTED          => "The transaction type is not supported",
        TransactionResultCode::FEE_BUMP_INNER_FAILED  => "The fee bump inner transaction failed",
        TransactionResultCode::BAD_SPONSORSHIP        => "The sponsorship is not confirmed",
        TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP => "",
        TransactionResultCode::MALFORMED              => "",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof TransactionResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
