<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AssetType
 */
class AssetTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => AssetType::ASSET_TYPE_NATIVE,
            1 => AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4,
            2 => AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12,
            3 => AssetType::ASSET_TYPE_POOL_SHARE,
        ];
        $memoType = new AssetType();

        $this->assertEquals($expected, $memoType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $memoType = AssetType::native();
        $this->assertEquals(AssetType::ASSET_TYPE_NATIVE, $memoType->getType());
    }

    /**
     * @test
     * @covers ::native
     */
    public function it_can_be_instantiated_as_a_native_asset_type()
    {
        $assetType = AssetType::native();

        $this->assertInstanceOf(AssetType::class, $assetType);
        $this->assertEquals(AssetType::ASSET_TYPE_NATIVE, $assetType->getType());
    }

    /**
     * @test
     * @covers ::alphaNum4
     */
    public function it_can_be_instantiated_as_an_alphanum_4_asset_type()
    {
        $assetType = AssetType::alphaNum4();

        $this->assertInstanceOf(AssetType::class, $assetType);
        $this->assertEquals(AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4, $assetType->getType());
    }

    /**
     * @test
     * @covers ::alphaNum12
     */
    public function it_can_be_instantiated_as_an_alphanum_12_asset_type()
    {
        $assetType = AssetType::alphaNum12();

        $this->assertInstanceOf(AssetType::class, $assetType);
        $this->assertEquals(AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12, $assetType->getType());
    }

    /**
     * @test
     * @covers ::poolShare
     */
    public function it_can_be_instantiated_as_a_pool_share_asset_type()
    {
        $assetType = AssetType::poolShare();

        $this->assertInstanceOf(AssetType::class, $assetType);
        $this->assertEquals(AssetType::ASSET_TYPE_POOL_SHARE, $assetType->getType());
    }
}
