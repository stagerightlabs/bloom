<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<Asset>
 */
class PathPaymentAssetList extends Arr implements XdrArray
{
    /**
     * Properties
     */
    public const MAX_LENGTH = 5;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return Asset::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return self::MAX_LENGTH;
    }

    /**
     * Instantiate an empty array.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }

    /**
     * Convert a native array of assets, or asset strings, into an asset list.
     *
     * @param PathPaymentAssetList|array<Asset|string> $assets
     * @return static
     */
    public static function normalize(PathPaymentAssetList|array $assets): static
    {
        if ($assets instanceof PathPaymentAssetList) {
            /** @var static */
            return Copy::deep($assets);
        }

        return array_reduce($assets, function ($list, $asset) {
            $asset = is_string($asset)
                ? Asset::fromNativeString($asset)
                : $asset;

            return $list->push($asset);
        }, new static());
    }
}
