<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolType;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class LiquidityPoolEntryBody extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LiquidityPoolType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT => LiquidityPoolEntryConstantProduct::class,
        ];
    }

    /**
     * Create a new text memo.
     *
     * @param LiquidityPoolEntryConstantProduct $liquidityPoolEntryConstantProduct
     * @return static
     */
    public static function wrapLiquidityPoolEntryConstantProduct(LiquidityPoolEntryConstantProduct $liquidityPoolEntryConstantProduct): static
    {
        $liquidityPoolEntryBody = new static();
        $liquidityPoolEntryBody->discriminator = LiquidityPoolType::liquidityPoolConstantProduct();
        $liquidityPoolEntryBody->value = $liquidityPoolEntryConstantProduct;

        return $liquidityPoolEntryBody;
    }

    /**
     * Return the underlying value.
     *
     * @return LiquidityPoolEntryConstantProduct|null
     */
    public function unwrap(): ?LiquidityPoolEntryConstantProduct
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
