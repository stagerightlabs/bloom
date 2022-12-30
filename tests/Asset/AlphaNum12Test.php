<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Asset;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\AlphaNum12;
use StageRightLabs\Bloom\Asset\AssetCode12;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\AlphaNum12
 */
class AlphaNum12Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_via_static_helper()
    {
        $alphaNum4 = AlphaNum12::of('ABCDEFGHIJKL', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $this->assertEquals('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $alphaNum4->toNativeString());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum12 = (new AlphaNum12())
            ->withAssetCode(AssetCode12::of('ABCDEFGHIJKL'))
            ->withIssuer(AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'));
        $buffer = XDR::fresh()->write($alphaNum12);

        $this->assertEquals('QUJDREVGR0hJSktMAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpS', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $alphaNum12 = XDR::fromBase64('QUJDREVGR0hJSktMAAAAAFXAH4dH3byGptDLjYonB1S62WdJwXveaBKged/eqXpS')
            ->read(AlphaNum12::class);

        $this->assertInstanceOf(AlphaNum12::class, $alphaNum12);
        $this->assertEquals('ABCDEFGHIJKL', $alphaNum12->getAssetCode()->toNativeString());
        $this->assertEquals(
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            $alphaNum12->getIssuer()->getAddress()
        );
    }

    /**
     * @test
     * @covers ::withAssetCode
     * @covers ::getAssetCode
     */
    public function it_accepts_an_asset_code()
    {
        $alphaNum12 = (new AlphaNum12())->withAssetCode(AssetCode12::of('ABCDEFGHIJKL'));
        $this->assertInstanceOf(AssetCode12::class, $alphaNum12->getAssetCode());
    }

    /**
     * @test
     * @covers ::withIssuer
     * @covers ::getIssuer
     */
    public function it_accepts_an_issuer()
    {
        $alphaNum12 = (new AlphaNum12())->withIssuer(
            AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS')
        );
        $this->assertInstanceOf(AccountId::class, $alphaNum12->getIssuer());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $alphaNum12 = (new AlphaNum12())
            ->withAssetCode(AssetCode12::of('ABCDEFGHIJKL'))
            ->withIssuer(AccountId::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'));
        $this->assertEquals('ABCDEFGHIJKL:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS', $alphaNum12->toNativeString());
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_returns_an_empty_string_if_no_issuer_is_set()
    {
        $this->assertEmpty((new AlphaNum12())->toNativeString());
    }
}
