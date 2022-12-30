<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class Asset extends Union implements XdrUnion
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
        ];
    }


    /**
     * Create a new instance from an alphanum object.
     *
     * @param AlphaNum4|AlphaNum12 $alphaNum
     * @return static
     */
    public static function of(AlphaNum4|AlphaNum12 $alphaNum): static
    {
        if ($alphaNum instanceof AlphaNum4) {
            return self::wrapAlphaNum4($alphaNum);
        }

        return self::wrapAlphaNum12($alphaNum);
    }

    /**
     * Create a new instance representing the native asset.
     *
     * @return static
     */
    public static function native(): static
    {
        $asset = new static();
        $asset->discriminator = AssetType::native();
        $asset->value = null;

        return $asset;
    }

    /**
     * Create a new instance that wraps an AlphaNum4.
     *
     * @param AlphaNum4 $alphaNum4
     * @return static
     */
    public static function wrapAlphaNum4(AlphaNum4 $alphaNum4): static
    {
        $asset = new static();
        $asset->discriminator = AssetType::alphaNum4();
        $asset->value = $alphaNum4;

        return $asset;
    }

    /**
     * Create a new instance that wraps an AlphaNum12.
     *
     * @param AlphaNum12 $alphaNum12
     * @return static
     */
    public static function wrapAlphaNum12(AlphaNum12 $alphaNum12): static
    {
        $asset = new static();
        $asset->discriminator = AssetType::alphaNum12();
        $asset->value = $alphaNum12;

        return $asset;
    }

    /**
     * Is this the native asset?
     *
     * @return bool
     */
    public function isNative(): bool
    {
        if (isset($this->discriminator) && $this->discriminator instanceof AssetType) {
            return $this->discriminator->getType() == AssetType::native();
        }

        return false;
    }

    /**
     * Return the underlying asset.
     *
     * @return AlphaNum4|AlphaNum12|null
     */
    public function unwrap(): AlphaNum4|AlphaNum12|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Allow the asset to be cast as a string in the format
     * "[asset code]:[issuer address]".
     *
     * @return string
     */
    public function __toString(): string
    {
        if ($this->isNative()) {
            return Bloom::NATIVE_ASSET_CODE;
        }

        if (!isset($this->discriminator) || !isset($this->value)) {
            return '';
        }

        return $this->value->__toString();
    }

    /**
     * Return the asset as a string in the format "[asset code]:[issuer address]".
     *
     * @return string
     */
    public function getCanonicalName(): string
    {
        return $this->__toString();
    }

    /**
     * Create a new instance by parsing an asset string.
     *
     * @param string $canonical
     * @return static
     * @throws InvalidAssetException
     */
    public static function fromNativeString(string $canonical): static
    {
        $parts = explode(':', $canonical);

        if (count($parts) == 1 && strtolower($parts[0]) == strtolower(Bloom::NATIVE_ASSET_CODE)) {
            return Asset::native();
        }

        if (count($parts) == 2 && mb_strlen($parts[0]) <= 4) {
            return Asset::wrapAlphaNum4(AlphaNum4::of($parts[0], strtoupper($parts[1])));
        }

        if (count($parts) == 2 && mb_strlen($parts[0]) <= 12) {
            return Asset::wrapAlphaNum12(AlphaNum12::of($parts[0], strtoupper($parts[1])));
        }

        throw new InvalidAssetException("The string '{$canonical}' does not represent a valid asset");
    }

    /**
     * Return the asset code as a string.
     *
     * @return string
     */
    public function getAssetCode(): string
    {
        if ($this->isNative()) {
            return Bloom::NATIVE_ASSET_CODE;
        }

        if (!isset($this->discriminator) || !isset($this->value)) {
            return '';
        }

        return $this->value->getAssetCode()->getCode();
    }

    /**
     * Return the address of the asset issuer as a string.
     *
     * @return string
     */
    public function getIssuerAddress(): string
    {
        if ($this->isNative()) {
            return '';
        }

        if (!isset($this->discriminator) || !isset($this->value)) {
            return '';
        }

        return $this->value->getIssuer()->getAddress();
    }

    /**
     * Compare two assets to determine sort order. Returns -1 if assetA < assetB,
     * 0 if assetA == assetB, 1 if assetA > assetB.
     *
     * @see https://github.com/stellar/js-stellar-base/blob/9fba30541d503062d6ba44bba15605bdbd8d84b6/src/asset.js#L215
     * @param Asset|string $assetA
     * @param Asset|string $assetB
     * @return int
     */
    public static function compare(Asset|string $assetA, Asset|string $assetB): int
    {
        if (is_string($assetA)) {
            $assetA = static::fromNativeString($assetA);
        }

        if (is_string($assetB)) {
            $assetB = static::fromNativeString($assetB);
        }

        // Compare the asset type discriminator value.
        if (
            $assetA->getXdrDiscriminator() instanceof XdrEnum
            && $assetB->getXdrDiscriminator() instanceof XdrEnum
            && $assetA->getXdrDiscriminator()->getXdrSelection() != $assetB->getXdrDiscriminator()->getXdrSelection()
        ) {
            return $assetA->getXdrDiscriminator()->getXdrSelection() <=> $assetB->getXdrDiscriminator()->getXdrSelection();
        }

        // Compare the asset codes.
        if (strtoupper($assetA->getAssetCode()) != strtoupper($assetB->getAssetCode())) {
            return strtoupper($assetA->getAssetCode()) <=> strtoupper($assetB->getAssetCode());
        }

        // Compare the issuer address.
        return $assetA->getIssuerAddress() <=> $assetB->getIssuerAddress();
    }
}
