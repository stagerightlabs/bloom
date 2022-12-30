<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class TxSetComponent extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return TxSetComponentType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            TxSetComponentType::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE => TxSetComponentTxsMaybeDiscountedFee::class,
        ];
    }

    /**
     * Create a new instance by wrapping a TxSetComponentTxsMaybeDiscountedFee object.
     *
     * @param TxSetComponentTxsMaybeDiscountedFee $txSetComponentTxsMaybeDiscountedFee
     * @return static
     */
    public static function wrapTxSetComponentTxsMaybeDiscountedFee(TxSetComponentTxsMaybeDiscountedFee $txSetComponentTxsMaybeDiscountedFee): static
    {
        $txSetComponent = new static();
        $txSetComponent->discriminator = TxSetComponentType::maybeDiscountedFee();
        $txSetComponent->value = $txSetComponentTxsMaybeDiscountedFee;

        return $txSetComponent;
    }

    /**
     * Return the underlying value.
     *
     * @return TxSetComponentTxsMaybeDiscountedFee|null
     */
    public function unwrap(): ?TxSetComponentTxsMaybeDiscountedFee
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
