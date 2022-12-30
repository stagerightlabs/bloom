<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntryConstantProduct;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LiquidityPoolEntryConstantProduct
 */
class LiquidityPoolEntryConstantProductTest extends TestCase
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
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesA(Int64::of(100))
            ->withReservesB(Int64::of(200))
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        $buffer = XDR::fresh()->write($liquidityPoolConstantProduct);

        $this->assertEquals(
            'AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQAAAAAAAAAZAAAAAAAAADIAAAAAAAAASwAAAAAAAABkA==',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function params_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withReservesA(Int64::of(100))
            ->withReservesB(Int64::of(200))
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        XDR::fresh()->write($liquidityPoolConstantProduct);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function reserves_a_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesB(Int64::of(200))
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        XDR::fresh()->write($liquidityPoolConstantProduct);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function reserves_b_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesA(Int64::of(100))
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        XDR::fresh()->write($liquidityPoolConstantProduct);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function total_pool_shares_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesA(Int64::of(100))
            ->withReservesB(Int64::of(200))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        XDR::fresh()->write($liquidityPoolConstantProduct);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function the_pool_share_trust_line_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters)
            ->withReservesA(Int64::of(100))
            ->withReservesB(Int64::of(200))
            ->withTotalPoolShares(Int64::of(300));
        XDR::fresh()->write($liquidityPoolConstantProduct);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liquidityPoolConstantProduct = XDR::fromBase64('AAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQAAAAAAAAAZAAAAAAAAADIAAAAAAAAASwAAAAAAAABkA==')
            ->read(LiquidityPoolEntryConstantProduct::class);

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProduct->getParams());
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesA());
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesB());
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getTotalPoolShares());
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getPoolSharesTrustlineCount());
    }

    /**
     * @test
     * @covers ::withParams
     * @covers ::getParams
     */
    public function it_accepts_params()
    {
        $alphaNum4 = AlphaNum4::of('ABCD', 'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $liquidityPoolConstantProductParameters = (new LiquidityPoolConstantProductParameters())
            ->withAssetA(Asset::of($alphaNum4))
            ->withAssetB(Asset::of($alphaNum4))
            ->withFee(100);
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withParams($liquidityPoolConstantProductParameters);

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(LiquidityPoolConstantProductParameters::class, $liquidityPoolConstantProduct->getParams());
    }

    /**
     * @test
     * @covers ::withReservesA
     * @covers ::getReservesA
     */
    public function it_accepts_an_int64_as_a_count_of_reserves_a()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withReservesA(Int64::of(100));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesA());
    }

    /**
     * @test
     * @covers ::withReservesA
     * @covers ::getReservesA
     */
    public function it_accepts_a_scaled_amount_as_a_count_of_reserves_a()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withReservesA(ScaledAmount::of(100));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesA());
        $this->assertEquals('1000000000', $liquidityPoolConstantProduct->getReservesA()->toNativeString());
    }

    /**
     * @test
     * @covers ::withReservesB
     * @covers ::getReservesB
     */
    public function it_accepts_an_int64_as_a_count_of_reserves_b()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withReservesB(Int64::of(200));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesB());
    }

    /**
     * @test
     * @covers ::withReservesB
     * @covers ::getReservesB
     */
    public function it_accepts_a_scaled_amount_as_a_count_of_reserves_b()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withReservesB(ScaledAmount::of(200));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getReservesB());
        $this->assertEquals('2000000000', $liquidityPoolConstantProduct->getReservesB()->toNativeString());
    }

    /**
     * @test
     * @covers ::withTotalPoolShares
     * @covers ::getTotalPoolShares
     */
    public function it_accepts_a_count_of_total_pool_shares()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withTotalPoolShares(Int64::of(300));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getTotalPoolShares());
    }

    /**
     * @test
     * @covers ::withPoolSharesTrustLineCount
     * @covers ::getPoolSharesTrustLineCount
     */
    public function it_accepts_a_count_of_pool_share_trust_lines()
    {
        $liquidityPoolConstantProduct = (new LiquidityPoolEntryConstantProduct())
            ->withPoolSharesTrustLineCount(Int64::of(400));

        $this->assertInstanceOf(LiquidityPoolEntryConstantProduct::class, $liquidityPoolConstantProduct);
        $this->assertInstanceOf(Int64::class, $liquidityPoolConstantProduct->getPoolSharesTrustlineCount());
    }
}
