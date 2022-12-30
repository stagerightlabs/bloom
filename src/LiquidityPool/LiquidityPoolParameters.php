<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\LiquidityPool;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolParameters extends Union implements XdrUnion
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
            LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT => LiquidityPoolConstantProductParameters::class,
        ];
    }

    /**
     * Create a new instance by wrapping a LiquidityPoolConstantProductParameters object.
     *
     * @param LiquidityPoolConstantProductParameters $liquidityPoolConstantProductParameters
     * @return static
     */
    public static function wrapLiquidityPoolConstantProductParameters(LiquidityPoolConstantProductParameters $liquidityPoolConstantProductParameters): static
    {
        $liquidityPoolParameters = new static();
        $liquidityPoolParameters->discriminator = LiquidityPoolType::liquidityPoolConstantProduct();
        $liquidityPoolParameters->value = $liquidityPoolConstantProductParameters;

        return $liquidityPoolParameters;
    }

    /**
     * Return the underlying value.
     *
     * @return LiquidityPoolConstantProductParameters|null
     */
    public function unwrap(): ?LiquidityPoolConstantProductParameters
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new instance from a set of parameters that describe a liquidity pool.
     *
     * @param Asset|string $assetA
     * @param Asset|string $assetB
     * @param Int32|int|null $fee
     * @param string $type
     * @return static
     * @throws InvalidArgumentException
     */
    public static function build(
        Asset|string $assetA,
        Asset|string $assetB,
        Int32|int $fee = null,
        string $type = LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT
    ): static {
        if (is_string($assetA)) {
            $assetA = Asset::fromNativeString($assetA);
        }

        if (is_string($assetB)) {
            $assetB = Asset::fromNativeString($assetB);
        }

        // Ensure that Asset A and Asset B are in the correct sorting order
        [$assetA, $assetB] = match (Asset::compare($assetA, $assetB)) {
            -1      => [$assetA, $assetB],
            1       => [$assetB, $assetA],
            default => throw new InvalidArgumentException('Attempting to reference a liquidity pool with an invalid asset combination')
        };

        // At the moment, "constant product" is the only type of Liquidity Pool
        // definition. If more are added in the future we will use the $type
        // parameter here to distinguish how to proceed with construction.

        // Build the constant product parameters object
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA($assetA)
            ->withAssetB($assetB)
            ->withFee($fee);

        return self::wrapLiquidityPoolConstantProductParameters($liquidityPoolConstantProductParameters);
    }

    /**
     * Calculate the liquidity pool ID from its parameters.
     *
     * @return PoolId|null
     */
    public function getPoolId(): ?PoolId
    {
        return PoolId::make(XDR::fresh()->write($this)->buffer());
    }
}
