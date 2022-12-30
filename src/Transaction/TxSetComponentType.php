<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class TxSetComponentType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE = 'txsetCompTxsMaybeDiscountedFee'; // txs with effective fee <= bid derived from a base fee (if any). If base fee is not specified, no discount is applied.

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE.
     *
     * @return static
     */
    public static function maybeDiscountedFee(): static
    {
        return (new static())->withValue(self::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE);
    }
}
