<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\AssetCode4;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AlphaNum4
 */
class AlphaNum4Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_via_static_helper()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertEquals('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $alphaNum4->toNativeString());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = (new AlphaNum4())
            ->withAssetCode(AssetCode4::of('ABCD'))
            ->withIssuer(AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'));
        $buffer = XDR::fresh()->write($alphaNum4);

        $this->assertEquals('QUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $alphaNum4 = XDR::fromBase64('QUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6Ug==')
            ->read(AlphaNum4::class);

        $this->assertInstanceOf(AlphaNum4::class, $alphaNum4);
        $this->assertEquals('ABCD', $alphaNum4->getAssetCode()->toNativeString());
        $this->assertEquals(
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            $alphaNum4->getIssuer()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withAssetCode
     * @covers ::getAssetCode
     */
    public function it_accepts_an_asset_code()
    {
        $alphaNum4 = (new AlphaNum4())->withAssetCode(AssetCode4::of('ABCD'));
        $this->assertInstanceOf(AssetCode4::class, $alphaNum4->getAssetCode());
    }

    /**
     * @test
     * @covers ::withIssuer
     * @covers ::getIssuer
     */
    public function it_accepts_an_issuer()
    {
        $alphaNum4 = (new AlphaNum4())->withIssuer(
            AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')
        );
        $this->assertInstanceOf(AccountId::class, $alphaNum4->getIssuer());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $alphaNum4 = (new AlphaNum4())
            ->withAssetCode(AssetCode4::of('ABCD'))
            ->withIssuer(AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'));
        $this->assertEquals('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $alphaNum4->toNativeString());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_returns_an_empty_string_if_no_issuer_is_set()
    {
        $this->assertEmpty((new AlphaNum4())->toNativeString());
    }
}
