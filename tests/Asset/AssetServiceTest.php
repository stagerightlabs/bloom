<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetCode;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AssetService
 */
class AssetServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::native
     */
    public function it_returns_a_native_asset_object()
    {
        $bloom = new Bloom();
        $asset = $bloom->asset->native();

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertTrue($asset->isNative());
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_can_build_an_asset_object()
    {
        $bloom = new Bloom();
        $assetCode = AssetCode::fromNativeString('TEST');
        $accountId = AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = $bloom->asset->build($assetCode, $accountId);

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('TEST', $asset->getAssetCode());
        $this->assertEquals('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $asset->getIssuerAddress());
    }

    /**
     * @test
     * @covers ::build
     */
    public function it_can_build_an_asset_object_from_strings()
    {
        $bloom = new Bloom();
        $asset = $bloom->asset->build('TEST', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertEquals('TEST', $asset->getAssetCode());
        $this->assertEquals('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $asset->getIssuerAddress());
    }

    /**
     * @test
     * @covers ::fromString
     */
    public function it_can_create_an_asset_object_from_a_native_string()
    {
        $bloom = new Bloom();
        $asset = $bloom->asset->fromString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(Asset::class, $asset);
        $this->assertFalse($asset->isNative());
        $this->assertEquals('TEST', $asset->getAssetCode());
    }

    /**
     * @test
     * @covers ::maximumAmount
     */
    public function it_can_return_a_max_int_instance()
    {
        $bloom = new Bloom();
        $max = $bloom->asset->maximumAmount();

        $this->assertTrue($max->isEqualTo(Int64::max()));
    }
}
