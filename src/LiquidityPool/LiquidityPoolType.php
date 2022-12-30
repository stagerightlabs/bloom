<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\LiquidityPool;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LiquidityPoolType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const LIQUIDITY_POOL_CONSTANT_PRODUCT = 'liquidityPoolConstantProduct';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::LIQUIDITY_POOL_CONSTANT_PRODUCT,
        ];
    }

    /**
     * Return the selected liquidity pool type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_CONSTANT_PRODUCT.
     *
     * @return static
     */
    public static function liquidityPoolConstantProduct(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_CONSTANT_PRODUCT);
    }
}
