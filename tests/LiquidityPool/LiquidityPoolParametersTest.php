<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\LiquidityPool;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters
 */
class LiquidityPoolParametersTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LiquidityPoolType::class, LiquidityPoolParameters::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LiquidityPoolType::LIQUIDITY_POOL_CONSTANT_PRODUCT => LiquidityPoolConstantProductParameters::class,
        ];

        $this->assertEquals($expected, LiquidityPoolParameters::arms());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolConstantProductParameters
     * @covers ::unwrap
     */
    public function it_can_wrap_liquidity_pool_constant_product_parameters()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolParameters = LiquidityPoolParameters::wrapLiquidityPoolConstantProductParameters($liquidityPoolConstantProductParameters);

        $this->assertInstanceOf(LiquidityPoolParameters::class, $liquidityPoolParameters);
        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolParameters->unwrap());
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_can_be_instantiated_via_pool_assets()
    {
        $liquidityPoolParameters = LiquidityPoolParameters::build(
            assetA: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            fee: 1,
        );

        $this->assertInstanceOf(LiquidityPoolParameters::class, $liquidityPoolParameters);
        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolParameters->unwrap());
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_rejects_assets_that_are_identical()
    {
        $this->expectException(InvalidArgumentException::class);
        LiquidityPoolParameters::build(
            assetA: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
        );
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_swaps_assets_into_lexicographic_order()
    {
        $liquidityPoolParameters = LiquidityPoolParameters::build(
            assetA: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            fee: 1,
        );

        $this->assertEquals('ABCD', $liquidityPoolParameters->unwrap()->getAssetA()->getAssetCode());
        $this->assertEquals('TEST', $liquidityPoolParameters->unwrap()->getAssetB()->getAssetCode());
    }

    /**
     * @test
     * @covers ::getPoolId
     * @dataProvider providePoolIdData
     * @see https://github.com/stellar/js-stellar-base/blob/79254da34ff8e171bd09c088161bda69969300e1/test/unit/get_liquidity_pool_id_test.js
     * @see https://github.com/stellar/stellar-core/blob/c5f6349b240818f716617ca6e0f08d295a6fad9a/src/transactions/test/LiquidityPoolTradeTests.cpp#L430-L526
     */
    public function it_can_generate_a_hashed_pool_id($assetA, $assetB, $expected)
    {
        $liquidityPoolParameters = LiquidityPoolParameters::build(
            assetA: $assetA,
            assetB: $assetB,
        );

        $this->assertEquals($expected, $liquidityPoolParameters->getPoolId()->toHex());
    }

    public function providePoolIdData(): array
    {
        return [
            ['XLM', 'AbC:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', 'c17f36fbd210e43dca1cda8edc5b6c0f825fcb72b39f0392fd6309844d77ff7d'],
            ['XLM', 'AbCd:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', '80e0c5dc79ed76bb7e63681f6456136762f0d01ede94bb379dbc793e66db35e6'],
            ['XLM', 'AbCdEfGhIjK:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', 'd2306c6e8532f99418e9d38520865e1c1059cddb6793da3cc634224f2ffb5bd4'],
            ['XLM', 'AbCdEfGhIjKl:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', '807e9e66653b5fda4dd4e672ff64a929fc5fdafe152eeadc07bb460c4849d711'],
            ['aBc:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', 'aBc:GCV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66AJDIVTYTFGN', '5d7188454299529856586e81ea385d2c131c6afdd9d58c82e9aa558c16522fea'],
            ['aBcDeFgHiJkL:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', 'aBcDeFgHiJkL:GCV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66AJDIVTYTFGN', '93fa82ecaabe987461d1e3c8e0fd6510558b86ac82a41f7c70b112281be90c71'],
            ['aBc:GAASGRLHRGV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66OUL', 'aBcDeFgHiJk:GCV433YBENCWPCNLZXXQCI2FM6E2XTPPAERUKZ4JVPG66AJDIVTYTFGN', 'c0d4c87bbaade53764b904fde2901a0353af437e9d3a976f1252670b85a36895'],
        ];
    }
}
