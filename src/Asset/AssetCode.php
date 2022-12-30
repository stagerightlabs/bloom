<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class AssetCode extends Union implements XdrUnion
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
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4  => AssetCode4::class,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12 => AssetCode12::class,
        ];
    }

    /**
     * Build a new instance from an AlphaNum4.
     *
     * @param AssetCode4 $assetCode4
     * @return static
     */
    public static function wrapAssetCode4(AssetCode4 $assetCode4): static
    {
        $assetCode = new static();
        $assetCode->discriminator = AssetType::alphaNum4();
        $assetCode->value = $assetCode4;

        return $assetCode;
    }

    /**
     * Build a new instance from an AlphaNum4.
     *
     * @param AssetCode12 $assetCode12
     * @return static
     */
    public static function wrapAssetCode12(AssetCode12 $assetCode12): static
    {
        $assetCode = new static();
        $assetCode->discriminator = AssetType::alphaNum12();
        $assetCode->value = $assetCode12;

        return $assetCode;
    }

    /**
     * Return the underlying asset code.
     *
     * @return AssetCode4|AssetCode12|null
     */
    public function unwrap(): AssetCode4|AssetCode12|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the asset code as a string.
     *
     * @return string|null
     */
    public function toNativeString(): ?string
    {
        if (isset($this->value)) {
            return $this->value->toNativeString();
        }

        return null;
    }

    /**
     * Create a new instance from a string.
     *
     * @param string $code
     * @return static
     */
    public static function fromNativeString(string $code): static
    {
        if (empty($code)) {
            throw new InvalidAssetException('Attempting to create an asset code from an empty string');
        }

        if (strlen($code) > 12) {
            throw new InvalidAssetException('The provided asset code is longer than the allowed 12 characters');
        }

        if (strlen($code) <= 4) {
            return self::wrapAssetCode4(AssetCode4::of($code));
        }

        return self::wrapAssetCode12(AssetCode12::of($code));
    }
}
