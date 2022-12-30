<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\TrustLineAsset
 */
class TrustLineAssetTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AssetType::class, TrustLineAsset::getXdrDiscriminatorType());
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
            AssetType::ASSET_TYPE_POOL_SHARE         => PoolId::class,
        ];

        $this->assertEquals($expected, TrustLineAsset::arms());
    }

    /**
     * @test
     * @covers ::native
     * @covers ::unwrap
     */
    public function it_can_wrap_a_native_asset()
    {
        $trustLineAsset = TrustLineAsset::native();
        $this->assertNull($trustLineAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum4
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alpha_num_4()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $trustLineAsset = TrustLineAsset::wrapAlphaNum4($alphaNum4);
        $this->assertInstanceOf(AlphaNum4::class, $trustLineAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum12
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alpha_num_12()
    {
        $alphaNum12 = AlphaNum12::of('ABCDEFGHIJKL', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $trustLineAsset = TrustLineAsset::wrapAlphaNum12($alphaNum12);
        $this->assertInstanceOf(AlphaNum12::class, $trustLineAsset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapPoolId
     * @covers ::unwrap
     */
    public function it_can_wrap_a_pool_id()
    {
        $poolId = PoolId::make('1');
        $trustLineAsset = TrustLineAsset::wrapPoolId($poolId);
        $this->assertInstanceOf(PoolId::class, $trustLineAsset->unwrap());
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_instantiated_from_an_alphanum4_asset()
    {
        $asset = Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $trustLineAsset = TrustLineAsset::fromAsset($asset);

        $this->assertInstanceOf(TrustLineAsset::class, $trustLineAsset);
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_instantiated_from_an_alphanum12_asset()
    {
        $asset = Asset::fromNativeString('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $trustLineAsset = TrustLineAsset::fromAsset($asset);

        $this->assertInstanceOf(TrustLineAsset::class, $trustLineAsset);
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_instantiated_from_the_native_asset()
    {
        $asset = Asset::native();
        $trustLineAsset = TrustLineAsset::fromAsset($asset);

        $this->assertInstanceOf(TrustLineAsset::class, $trustLineAsset);
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_can_be_instantiated_from_an_asset_string()
    {
        $trustLineAsset = TrustLineAsset::fromAsset('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertInstanceOf(TrustLineAsset::class, $trustLineAsset);
    }

    /**
     * @test
     * @covers ::fromAsset
     */
    public function it_cannot_be_instantiated_from_an_unknown_asset_type()
    {
        $this->expectException(InvalidAssetException::class);
        TrustLineAsset::fromAsset(new Asset());
    }
}
