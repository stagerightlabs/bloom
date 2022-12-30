<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\LiquidityPool;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolService
 */
class LiquidityPoolServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::pool
     */
    public function it_can_build_a_liquidity_pool_parameters_object()
    {
        $bloom = new Bloom();
        $liquidityPoolParameters = $bloom->liquidityPool->pool(
            assetA: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
        );

        $this->assertInstanceOf(LiquidityPoolParameters::class, $liquidityPoolParameters);
        $this->assertEquals('ABCD', $liquidityPoolParameters->unwrap()->getAssetA()->getAssetCode());
        $this->assertEquals('TEST', $liquidityPoolParameters->unwrap()->getAssetB()->getAssetCode());
    }
}
