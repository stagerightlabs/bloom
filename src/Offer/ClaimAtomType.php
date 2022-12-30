<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Offer;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimAtomType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAIM_ATOM_TYPE_V0 = 'claimAtomTypeV0';
    public const CLAIM_ATOM_TYPE_ORDER_BOOK = 'claimAtomTypeOrderBook';
    public const CLAIM_ATOM_TYPE_LIQUIDITY_POOL = 'claimAtomTypeLiquidityPool';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::CLAIM_ATOM_TYPE_V0,
            1 => self::CLAIM_ATOM_TYPE_ORDER_BOOK,
            2 => self::CLAIM_ATOM_TYPE_LIQUIDITY_POOL,
        ];
    }

    /**
     * Return the selected result type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as CLAIM_ATOM_TYPE_V0.
     *
     * @return static
     */
    public static function v0(): static
    {
        return (new static())->withValue(self::CLAIM_ATOM_TYPE_V0);
    }

    /**
     * Create a new instance pre-selected as CLAIM_ATOM_TYPE_ORDER_BOOK.
     *
     * @return static
     */
    public static function orderBook(): static
    {
        return (new static())->withValue(self::CLAIM_ATOM_TYPE_ORDER_BOOK);
    }

    /**
     * Create a new instance pre-selected as CLAIM_ATOM_TYPE_LIQUIDITY_POOL.
     *
     * @return static
     */
    public static function liquidityPool(): static
    {
        return (new static())->withValue(self::CLAIM_ATOM_TYPE_LIQUIDITY_POOL);
    }
}
