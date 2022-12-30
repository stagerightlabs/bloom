<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\LiquidityPool;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Service;

class LiquidityPoolService extends Service
{
    /**
     * Generate a liquidity pool object from its component assets.
     *
     * @param Asset|string $assetA
     * @param Asset|string $assetB
     * @param Int32|int|null $fee
     * @param string $type
     * @return LiquidityPoolParameters
     */
    public function pool(
        Asset|string $assetA,
        Asset|string $assetB,
        Int32|int $fee = null,
        string $type = LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT
    ): LiquidityPoolParameters {
        return LiquidityPoolParameters::build($assetA, $assetB, $fee, $type);
    }
}
