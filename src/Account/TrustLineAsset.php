<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class TrustLineAsset extends Union implements XdrUnion
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
            AssetType::ASSET_TYPE_POOL_SHARE         => PoolId::class,
        ];
    }

    /**
     * Create a new instance representing the native asset.
     *
     * @return static
     */
    public static function native(): static
    {
        $trustLineAsset = new static();
        $trustLineAsset->discriminator = AssetType::native();
        $trustLineAsset->value = null;

        return $trustLineAsset;
    }

    /**
     * Create a new instance by wrapping an AlphaNum4.
     *
     * @param AlphaNum4 $alphaNum4
     * @return static
     */
    public static function wrapAlphaNum4(AlphaNum4 $alphaNum4): static
    {
        $trustLineAsset = new static();
        $trustLineAsset->discriminator = AssetType::alphaNum4();
        $trustLineAsset->value = $alphaNum4;

        return $trustLineAsset;
    }

    /**
     * Create a new instance by wrapping an AlphaNum12.
     *
     * @param AlphaNum12 $alphaNum12
     * @return static
     */
    public static function wrapAlphaNum12(AlphaNum12 $alphaNum12): static
    {
        $trustLineAsset = new static();
        $trustLineAsset->discriminator = AssetType::alphaNum12();
        $trustLineAsset->value = $alphaNum12;

        return $trustLineAsset;
    }

    /**
     * Create a new instance by wrapping a liquidity pool Id.
     *
     * @param PoolId $poolId
     * @return static
     */
    public static function wrapPoolId(PoolId $poolId): static
    {
        $trustLineAsset = new static();
        $trustLineAsset->discriminator = AssetType::poolShare();
        $trustLineAsset->value = $poolId;

        return $trustLineAsset;
    }

    /**
     * Return the underlying value.
     *
     * @return AlphaNum4|AlphaNum12|PoolId|null
     */
    public function unwrap(): AlphaNum4|AlphaNum12|PoolId|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new instance from an asset.
     *
     * @param Asset|string $asset
     * @return static
     */
    public static function fromAsset(Asset|string $asset): static
    {
        if (is_string($asset)) {
            $asset = Asset::fromNativeString($asset);
        }

        if ($asset->isNative()) {
            return self::native();
        }

        if ($asset->unwrap() instanceof AlphaNum4) {
            return self::wrapAlphaNum4($asset->unwrap());
        }

        if ($asset->unwrap() instanceof AlphaNum12) {
            return self::wrapAlphaNum12($asset->unwrap());
        }

        throw new InvalidAssetException('Attempting to create a TrustLineAsset from an unknown asset type');
    }
}
