<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Operation\OperationResultList;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class InnerTransactionResultResult extends Union implements XdrUnion
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
            TransactionResultCode::BAD_SPONSORSHIP        => XDR::VOID,
            TransactionResultCode::BAD_MIN_SEQ_AGE_OR_GAP => XDR::VOID,
            TransactionResultCode::MALFORMED              => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping a list of successful operation results.
     *
     * @param OperationResultList $operationResultList
     * @return static
     */
    public static function wrapSuccessfulOperationResultList(OperationResultList $operationResultList): static
    {
        $innerTransactionResultResult = new static();
        $innerTransactionResultResult->discriminator = TransactionResultCode::success();
        $innerTransactionResultResult->value = $operationResultList;

        return $innerTransactionResultResult;
    }

    /**
     * Create a new instance by wrapping a list of failed operation results.
     *
     * @param OperationResultList $operationResultList
     * @return static
     */
    public static function wrapFailedOperationResultList(OperationResultList $operationResultList): static
    {
        $innerTransactionResultResult = new static();
        $innerTransactionResultResult->discriminator = TransactionResultCode::failed();
        $innerTransactionResultResult->value = $operationResultList;

        return $innerTransactionResultResult;
    }

    /**
     * Return the underlying operation result list, if present.
     *
     * @return OperationResultList|null
     */
    public function unwrap(): ?OperationResultList
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Indicate whether this class represents failed or successful operation results.
     *
     * @return string
     */
    public function getType(): ?string
    {
        // TransactionResultCode: fee bump inner success = 0
        if (isset($this->discriminator) && $this->discriminator instanceof XdrEnum && $this->discriminator->getXdrSelection() == 0) {
            return TransactionResultCode::SUCCESS;
        }

        // TransactionResultCode: failed = -1
        if (isset($this->discriminator) && $this->discriminator instanceof XdrEnum && $this->discriminator->getXdrSelection() == -1) {
            return TransactionResultCode::FAILED;
        }

        return null;
    }
}
