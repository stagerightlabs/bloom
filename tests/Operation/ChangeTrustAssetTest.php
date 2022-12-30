<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\Operation\ChangeTrustAsset;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ChangeTrustAsset
 */
class ChangeTrustAssetTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AssetType::class, ChangeTrustAsset::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            AssetType::ASSET_TYPE_NATIVE             => XDR::VOID,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4  => AlphaNum4::class,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12 => AlphaNum12::class,
            AssetType::ASSET_TYPE_POOL_SHARE         => LiquidityPoolParameters::class,
        ];

        $this->assertEquals($expected, ChangeTrustAsset::arms());
    }

    /**
     * @test
     * @covers ::wrapNativeAsset
     * @covers ::unwrap
     */
    public function it_can_wrap_the_native_asset()
    {
        $changeTrustAsset = ChangeTrustAsset::wrapNativeAsset();

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertNull($changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum4
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alphanum_4()
    {
        $changeTrustAsset = ChangeTrustAsset::wrapAlphaNum4(new AlphaNum4());

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(AlphaNum4::class, $changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum12
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alphanum_12()
    {
        $changeTrustAsset = ChangeTrustAsset::wrapAlphaNum12(new AlphaNum12());

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(AlphaNum12::class, $changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolShare
     * @covers ::unwrap
     */
    public function it_can_wrap_a_liquidity_pool_share()
    {
        $changeTrustAsset = ChangeTrustAsset::wrapLiquidityPoolShare(new LiquidityPoolParameters());

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(LiquidityPoolParameters::class, $changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_made_from_a_native_asset()
    {
        $changeTrustAsset = ChangeTrustAsset::fromAsset(Asset::native());

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertNull($changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_made_from_a_alphanum4_asset()
    {
        $changeTrustAsset = ChangeTrustAsset::fromAsset(Asset::fromNativeString(
            'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'
        ));

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(AlphaNum4::class, $changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_made_from_a_alphanum12_asset()
    {
        $changeTrustAsset = ChangeTrustAsset::fromAsset(Asset::fromNativeString(
            'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'
        ));

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(AlphaNum12::class, $changeTrustAsset->unwrap());
    }

    /**
     * @test
     * @covers ::fromAsset
     * @covers ::fromLiquidityPoolParameters
     */
    public function it_can_be_made_from_a_liquidity_pool_asset()
    {
        $liquidityPoolParameters = LiquidityPoolParameters::build(
            assetA: 'ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            assetB: 'TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            fee: 1,
        );
        $changeTrustAsset = ChangeTrustAsset::fromAsset($liquidityPoolParameters);

        $this->assertInstanceOf(ChangeTrustAsset::class, $changeTrustAsset);
        $this->assertInstanceOf(LiquidityPoolParameters::class, $changeTrustAsset->unwrap());
    }
}
