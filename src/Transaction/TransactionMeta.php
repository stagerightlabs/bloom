<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Operation\OperationMetaList;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TransactionMeta extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return XDR::INT;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            0 => OperationMetaList::class,
            1 => TransactionMetaV1::class,
            2 => TransactionMetaV2::class,
        ];
    }

    /**
     * Create a new instance by wrapping an operation meta list.
     *
     * @param OperationMetaList $operations
     * @return static
     */
    public static function wrapOperationMetaList(OperationMetaList $operations): static
    {
        $transactionMeta = new static();
        $transactionMeta->discriminator = 0;
        $transactionMeta->value = $operations;

        return $transactionMeta;
    }

    /**
     * Create a new instance by wrapping a TransactionMetaV1 object.
     *
     * @param TransactionMetaV1 $transactionMetaV1
     * @return static
     */
    public static function wrapTransactionMetaV1(TransactionMetaV1 $transactionMetaV1): static
    {
        $transactionMeta = new static();
        $transactionMeta->discriminator = 1;
        $transactionMeta->value = $transactionMetaV1;

        return $transactionMeta;
    }

    /**
     * Create a new instance by wrapping a TransactionMetaV2 object.
     *
     * @param TransactionMetaV2 $transactionMetaV2
     * @return static
     */
    public static function wrapTransactionMetaV2(TransactionMetaV2 $transactionMetaV2): static
    {
        $transactionMeta = new static();
        $transactionMeta->discriminator = 2;
        $transactionMeta->value = $transactionMetaV2;

        return $transactionMeta;
    }

    /**
     * Return the underlying value.
     *
     * @return OperationMetaList|TransactionMetaV1|TransactionMetaV2|null
     */
    public function unwrap(): OperationMetaList|TransactionMetaV1|TransactionMetaV2|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
