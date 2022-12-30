<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\AssetCode;
use StageRightLabs\Bloom\Asset\AssetCode12;
use StageRightLabs\Bloom\Asset\AssetCode4;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AssetCode
 */
class AssetCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AssetType::class, AssetCode::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_4  => AssetCode4::class,
            AssetType::ASSET_TYPE_CREDIT_ALPHANUM_12 => AssetCode12::class,
        ];

        $this->assertEquals($expected, AssetCode::arms());
    }

    /**
     * @test
     * @covers ::wrapAssetCode4
     * @covers ::unwrap
     * @covers ::toNativeString
     */
    public function it_can_wrap_an_asset_code_4()
    {
        $assetCode = AssetCode::wrapAssetCode4(AssetCode4::of('ABCD'));

        $this->assertInstanceOf(AssetCode4::class, $assetCode->unwrap());
        $this->assertEquals('ABCD', $assetCode->toNativeString());
    }

    /**
     * @test
     * @covers ::wrapAssetCode12
     * @covers ::unwrap
     * @covers ::toNativeString
     */
    public function it_can_wrap_an_asset_code_12()
    {
        $assetCode = AssetCode::wrapAssetCode12(AssetCode12::of('ABCDEFGHIJKL'));

        $this->assertInstanceOf(AssetCode12::class, $assetCode->unwrap());
        $this->assertEquals('ABCDEFGHIJKL', $assetCode->toNativeString());
    }

    /**
     * @test
     * @covers ::toNativeString
     */
    public function it_returns_null_when_no_value_is_present()
    {
        $this->assertNull((new AssetCode())->toNativeString());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $assetCode4 = AssetCode::fromNativeString('ABCD');
        $this->assertInstanceOf(AssetCode::class, $assetCode4);
        $this->assertInstanceOf(AssetCode4::class, $assetCode4->unwrap());

        $assetCode12 = AssetCode::fromNativeString('ABCDEFGHIJKL');
        $this->assertInstanceOf(AssetCode::class, $assetCode12);
        $this->assertInstanceOf(AssetCode12::class, $assetCode12->unwrap());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_rejects_invalid_asset_code_strings_that_are_too_long()
    {
        $this->expectException(InvalidAssetException::class);
        AssetCode::fromNativeString(str_repeat('A', 13));
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_rejects_empty_asset_code_strings()
    {
        $this->expectException(InvalidAssetException::class);
        AssetCode::fromNativeString('');
    }
}
