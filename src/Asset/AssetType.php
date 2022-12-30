<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class AssetType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ASSET_TYPE_NATIVE = 'assetTypeNative';
    public const ASSET_TYPE_CREDIT_ALPHANUM_4 = 'assetTypeCreditAlphanum4';
    public const ASSET_TYPE_CREDIT_ALPHANUM_12 = 'assetTypeCreditAlphanum12';
    public const ASSET_TYPE_POOL_SHARE = 'assetTypePoolShare';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::ASSET_TYPE_NATIVE,
            1 => self::ASSET_TYPE_CREDIT_ALPHANUM_4,
            2 => self::ASSET_TYPE_CREDIT_ALPHANUM_12,
            3 => self::ASSET_TYPE_POOL_SHARE,
        ];
    }

    /**
     * Return the selected asset type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as ASSET_TYPE_NATIVE.
     *
     * @return static
     */
    public static function native(): static
    {
        return (new static())->withValue(self::ASSET_TYPE_NATIVE);
    }

    /**
     * Create a new instance pre-selected as ASSET_TYPE_CREDIT_ALPHANUM_4.
     *
     * @return static
     */
    public static function alphaNum4(): static
    {
        return (new static())->withValue(self::ASSET_TYPE_CREDIT_ALPHANUM_4);
    }

    /**
     * Create a new instance pre-selected as ASSET_TYPE_CREDIT_ALPHANUM_12.
     *
     * @return static
     */
    public static function alphaNum12(): static
    {
        return (new static())->withValue(self::ASSET_TYPE_CREDIT_ALPHANUM_12);
    }

    /**
     * Create a new instance pre-selected as ASSET_TYPE_POOL_SHARE.
     *
     * @return static
     */
    public static function poolShare(): static
    {
        return (new static())->withValue(self::ASSET_TYPE_POOL_SHARE);
    }
}
