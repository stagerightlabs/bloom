<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetCode12;
use StageRightLabs\Bloom\Asset\AssetCode4;
use StageRightLabs\Bloom\Asset\AssetType;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\Asset
 */
class AssetTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(AssetType::class, Asset::getXdrDiscriminatorType());
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
        ];

        $this->assertEquals($expected, Asset::arms());
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_statically()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum4);
        $this->assertInstanceOf(Asset::class, $asset);

        $alphaNum12 = AlphaNum12::of('ABCDEFGHIJKL', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::of($alphaNum12);
        $this->assertInstanceOf(Asset::class, $asset);
    }

    /**
     * @test
     * @covers ::native
     * @covers ::isNative
     * @covers ::unwrap
     */
    public function it_can_represent_the_native_asset()
    {
        $asset = Asset::native();

        $this->assertTrue($asset->isNative());
        $this->assertNull($asset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum4
     * @covers ::isNative
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alphanum_4()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::wrapAlphaNum4($alphaNum4);

        $this->assertFalse($asset->isNative());
        $this->assertInstanceOf(AlphaNum4::class, $asset->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAlphaNum12
     * @covers ::isNative
     * @covers ::unwrap
     */
    public function it_can_wrap_an_alphanum_12()
    {
        $alphaNum12 = AlphaNum12::of('ABCDEFGHIJKL', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::wrapAlphaNum12($alphaNum12);

        $this->assertFalse($asset->isNative());
        $this->assertInstanceOf(AlphaNum12::class, $asset->unwrap());
    }

    /**
     * @test
     * @covers ::isNative
     */
    public function it_knows_it_is_not_a_native_asset_when_no_discriminator_is_present()
    {
        $asset = new Asset();
        $this->assertFalse($asset->isNative());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::getCanonicalName
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $alphaNum12 = AlphaNum12::of('ABCDEFGHIJKL', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset = Asset::wrapAlphaNum12($alphaNum12);
        $native = Asset::native();

        $this->assertEquals(
            'ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            $asset->getCanonicalName()
        );
        $this->assertEquals('XLM', $native->getCanonicalName());
        $this->assertEquals('', (new Asset())->getCanonicalName());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_can_be_read_from_a_native_string()
    {
        $asset4 = Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset12 = Asset::fromNativeString('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $native = Asset::fromNativeString('XLM');

        $this->assertInstanceOf(Asset::class, $asset4);
        $this->assertInstanceOf(AlphaNum4::class, $asset4->unwrap());
        $this->assertInstanceOf(AssetCode4::class, $asset4->unwrap()->getAssetCode());
        $this->assertInstanceOf(Asset::class, $asset12);
        $this->assertInstanceOf(AlphaNum12::class, $asset12->unwrap());
        $this->assertInstanceOf(AssetCode12::class, $asset12->unwrap()->getAssetCode());
        $this->assertInstanceOf(Asset::class, $native);
        $this->assertTrue($native->isNative());
    }

    /**
     * @test
     * @covers ::fromNativeString
     */
    public function it_throws_an_exception_when_interpreting_an_invalid_asset_string()
    {
        $this->expectException(InvalidAssetException::class);
        Asset::fromNativeString('foo');
    }

    /**
     * @test
     * @covers ::getAssetCode
     */
    public function it_returns_the_asset_code_as_a_string()
    {
        $asset4 = Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset12 = Asset::fromNativeString('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $native = Asset::fromNativeString('XLM');
        $empty = new Asset();

        $this->assertEquals('TEST', $asset4->getAssetCode());
        $this->assertEquals('ABCDEFGHIJKL', $asset12->getAssetCode());
        $this->assertEquals('XLM', $native->getAssetCode());
        $this->assertEmpty($empty->getAssetCode());
    }

    /**
     * @test
     * @covers ::getIssuerAddress
     */
    public function it_returns_the_issuer_address_as_a_string()
    {
        $asset4 = Asset::fromNativeString('TEST:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $asset12 = Asset::fromNativeString('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $native = Asset::fromNativeString('XLM');
        $empty = new Asset();

        $this->assertEquals('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $asset4->getIssuerAddress());
        $this->assertEquals('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $asset12->getIssuerAddress());
        $this->assertEmpty($native->getIssuerAddress());
        $this->assertEmpty($empty->getIssuerAddress());
    }

    /**
     * @test
     * @covers ::compare
     * @dataProvider provideAssetComparisonData
     * @see https://github.com/stellar/js-stellar-base/blob/79254da34ff8e171bd09c088161bda69969300e1/test/unit/asset_test.js#L281
     */
    public function it_can_compare_two_assets($assetA, $assetB, $expected)
    {
        $this->assertEquals($expected, Asset::compare($assetA, $assetB));
    }

    public function provideAssetComparisonData(): array
    {
        return [
            ['XLM', 'XLM', 0],
            ['XLM', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', -1],
            ['XLM', 'ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', -1],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'XLM', 1],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 0],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', -1],
            ['ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'XLM', 1],
            ['ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 1],
            ['ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARSTANUM12:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 0],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'USDX:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', -1],
            ['USDX:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'USDX:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 0],
            ['USDX:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 1],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARST:GCEZWKCA5VLDNRLN3RPRJMRZOX3Z6G5CHCGSNFHEYVXM3XOJMDS674JZ', -1],
            ['ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 0],
            ['ARST:GCEZWKCA5VLDNRLN3RPRJMRZOX3Z6G5CHCGSNFHEYVXM3XOJMDS674JZ', 'ARST:GB7TAYRUZGE6TVT7NHP5SMIZRNQA6PLM423EYISAOAP3MKYIQMVYP2JO', 1],
        ];
    }
}
