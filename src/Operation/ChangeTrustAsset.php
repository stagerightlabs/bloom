<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ChangeTrustAsset extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return AssetType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            AssetType::ASSET_TYPE_NATIVE             => XDR::VOID,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4  => AlphaNum4::class,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12 => AlphaNum12::class,
            AssetType::ASSET_TYPE_POOL_SHARE         => LiquidityPoolParameters::class,
        ];
    }

    /**
     * Create a new instance that represents the native asset.
     *
     * @return static
     */
    public static function wrapNativeAsset(): static
    {
        $changeTrustAsset = new static();
        $changeTrustAsset->discriminator = AssetType::native();
        $changeTrustAsset->value = null;

        return $changeTrustAsset;
    }

    /**
     * Create a new instance that represents an AlphaNum4 asset.
     *
     * @param AlphaNum4 $alphaNum4
     * @return static
     */
    public static function wrapAlphaNum4(AlphaNum4 $alphaNum4): static
    {
        $changeTrustAsset = new static();
        $changeTrustAsset->discriminator = AssetType::alphaNum4();
        $changeTrustAsset->value = $alphaNum4;

        return $changeTrustAsset;
    }

    /**
     * Create a new instance that represents an AlphaNum12 asset.
     *
     * @param AlphaNum12 $alphaNum12
     * @return static
     */
    public static function wrapAlphaNum12(AlphaNum12 $alphaNum12): static
    {
        $changeTrustAsset = new static();
        $changeTrustAsset->discriminator = AssetType::alphaNum12();
        $changeTrustAsset->value = $alphaNum12;

        return $changeTrustAsset;
    }

    /**
     * Create a new instance that represents a liquidity pool share.
     *
     * @param LiquidityPoolParameters $liquidityPoolParameters
     * @return static
     */
    public static function wrapLiquidityPoolShare(LiquidityPoolParameters $liquidityPoolParameters): static
    {
        $changeTrustAsset = new static();
        $changeTrustAsset->discriminator = AssetType::poolShare();
        $changeTrustAsset->value = $liquidityPoolParameters;

        return $changeTrustAsset;
    }

    /**
     * Return the underlying value.
     *
     * @return AlphaNum4|AlphaNum12|LiquidityPoolParameters|null
     */
    public function unwrap(): AlphaNum4|AlphaNum12|LiquidityPoolParameters|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new instance from an asset object.
     *
     * @param Asset|LiquidityPoolParameters $asset
     * @return static
     */
    public static function fromAsset(Asset|LiquidityPoolParameters $asset): static
    {
        if ($asset->unwrap() instanceof AlphaNum4) {
            return self::wrapAlphaNum4($asset->unwrap());
        }

        if ($asset->unwrap() instanceof AlphaNum12) {
            return self::wrapAlphaNum12($asset->unwrap());
        }

        if ($asset instanceof LiquidityPoolParameters) {
            return self::fromLiquidityPoolParameters($asset);
        }

        return self::wrapNativeAsset();
    }

    /**
     * Create a new instance from a LiquidityPoolParameters instance.
     *
     * @param LiquidityPoolParameters $liquidityPoolParameters
     * @return static
     */
    public static function fromLiquidityPoolParameters(LiquidityPoolParameters $liquidityPoolParameters): static
    {
        return self::wrapLiquidityPoolShare($liquidityPoolParameters);
    }
}
