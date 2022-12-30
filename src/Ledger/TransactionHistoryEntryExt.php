<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TransactionHistoryEntryExt extends Union implements XdrUnion
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
            0 => XDR::VOID,
            1 => GeneralizedTransactionSet::class,
        ];
    }

    /**
     * Create an empty instance.
     *
     * @return static
     */
    public static function empty(): static
    {
        $transactionHistoryEntryExt = new static();
        $transactionHistoryEntryExt->discriminator = 0;
        $transactionHistoryEntryExt->value = null;

        return $transactionHistoryEntryExt;
    }

    /**
     * Create a new instance by wrapping a GeneralizedTransactionSet object.
     *
     * @param GeneralizedTransactionSet $generalizedTransactionSet
     * @return static
     */
    public static function wrapGeneralizedTransactionSet(GeneralizedTransactionSet $generalizedTransactionSet): static
    {
        $transactionHistoryEntryExt = new static();
        $transactionHistoryEntryExt->discriminator = 1;
        $transactionHistoryEntryExt->value = $generalizedTransactionSet;

        return $transactionHistoryEntryExt;
    }

    /**
     * Return the underlying value.
     *
     * @return GeneralizedTransactionSet|null
     */
    public function unwrap(): ?GeneralizedTransactionSet
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
