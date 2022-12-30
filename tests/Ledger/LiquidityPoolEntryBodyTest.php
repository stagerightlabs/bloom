<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntryBody;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntryConstantProduct;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolType;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LiquidityPoolEntryBody
 */
class LiquidityPoolEntryBodyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LiquidityPoolType::class, LiquidityPoolEntryBody::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT => LiquidityPoolEntryConstantProduct::class,
        ];

        $this->assertEquals($expected, LiquidityPoolEntryBody::arms());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolEntryConstantProduct
     * @covers ::unwrap
     */
    public function it_can_wrap_a_liquidity_pool_entry_constant_product()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesA(Int64::of(100))
            ->withReservesB(Int64::of(200))
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        $liquidityPoolEntryBody = LiquidityPoolEntryBody::wrapLiquidityPoolEntryConstantProduct($liquidityPoolConstantProduct);

        $this->assertInstanceOf(LiquidityPoolEntryBody::class, $liquidityPoolEntryBody);
        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolEntryBody->unwrap());
    }
}
