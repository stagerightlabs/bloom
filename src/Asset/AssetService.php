<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Service;

final class AssetService extends Service
{
    /**
     * Retrieve an asset object that represents the native XLM asset.
     *
     * @return Asset
     */
    public function native(): Asset
    {
        return Asset::native();
    }

    /**
     * Construct an asset instance from an asset code and an issuer address.
     *
     * @param AssetCode|string $assetCode
     * @param Addressable|string $issuer
     * @return Asset
     */
    public function build(AssetCode|string $assetCode, Addressable|string $issuer): Asset
    {
        if ($assetCode instanceof AssetCode) {
            $assetCode = $assetCode->toNativeString();
        }

        if ($issuer instanceof Addressable) {
            $issuer = $issuer->getAddress();
        }

        return Asset::fromNativeString($assetCode . ':' . $issuer);
    }

    /**
     * Convert a string representation of an asset into an Asset object.
     *
     * @param string $asset
     * @return Asset
     */
    public function fromString(string $asset): Asset
    {
        return Asset::fromNativeString($asset);
    }

    /**
     * Return a representation of the largest possible amount of an asset.
     *
     * @return Int64
     */
    public function maximumAmount(): Int64
    {
        return Int64::max();
    }
}
