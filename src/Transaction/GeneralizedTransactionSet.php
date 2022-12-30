<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class GeneralizedTransactionSet extends Union implements XdrUnion
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
            1 => TransactionSetV1::class,
        ];
    }

    /**
     * Create a new instance by wrapping a TransactionSetV1 object.
     *
     * @param TransactionSetV1 $transactionSetV1
     * @return static
     */
    public static function wrapTransactionSetV1(TransactionSetV1 $transactionSetV1): static
    {
        $generalizedTransactionSet = new static();
        $generalizedTransactionSet->discriminator = 1;
        $generalizedTransactionSet->value = $transactionSetV1;

        return $generalizedTransactionSet;
    }

    /**
     * Return the underlying value.
     *
     * @return TransactionSetV1|null
     */
    public function unwrap(): ?TransactionSetV1
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
