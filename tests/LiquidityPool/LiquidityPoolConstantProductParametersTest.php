<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters
 */
class LiquidityPoolConstantProductParametersTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $buffer = XDR::fresh()->write($liquidityPoolConstantProductParameters);

        $this->assertEquals(
            'AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQ=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_a_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        XDR::fresh()->write($liquidityPoolConstantProductParameters);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_asset_b_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withFee(100);
        XDR::fresh()->write($liquidityPoolConstantProductParameters);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_fee_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4));
        XDR::fresh()->write($liquidityPoolConstantProductParameters);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liquidityPoolConstantProductParameters = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQ=')
            ->read(LiquidityPoolConstantProductParameters::class);

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetA());
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetB());
        $this->assertInstanceOf(Int32::class, $liquidityPoolConstantProductParameters->getFee());
    }

    /**
     * @test
     * @covers ::withAssetA
     * @covers ::getAssetA
     */
    public function it_accepts_an_a_asset()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4));

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetA());
    }

    /**
     * @test
     * @covers ::withAssetA
     * @covers ::getAssetA
     */
    public function it_accepts_an_a_asset_string()
    {
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetA());
    }

    /**
     * @test
     * @covers ::withAssetB
     * @covers ::getAssetB
     */
    public function it_accepts_a_b_asset()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetB(Asset::of($alphaNum4));

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetB());
    }

    /**
     * @test
     * @covers ::withAssetB
     * @covers ::getAssetB
     */
    public function it_accepts_a_b_asset_string()
    {
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetB('ABCD:GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Asset::class, $liquidityPoolConstantProductParameters->getAssetB());
    }

    /**
     * @test
     * @covers ::withFee
     * @covers ::getFee
     */
    public function it_accepts_an_int_32_as_a_fee()
    {
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withFee(Int32::of(100));

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Int32::class, $liquidityPoolConstantProductParameters->getFee());
    }

    /**
     * @test
     * @covers ::withFee
     * @covers ::getFee
     */
    public function it_accepts_a_native_int_as_a_fee()
    {
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withFee(100);

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertInstanceOf(Int32::class, $liquidityPoolConstantProductParameters->getFee());
    }

    /**
     * @test
     * @covers ::withFee
     * @covers ::getFee
     */
    public function it_accepts_a_null_fee()
    {
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withFee();

        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProductParameters);
        $this->assertEquals(Bloom::LIQUIDITY_POOL_FEE_V18, $liquidityPoolConstantProductParameters->getFee()->toNativeInt());
    }
}
