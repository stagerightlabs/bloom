<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntry;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntryBody;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntryConstantProduct;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LiquidityPoolEntry
 */
class LiquidityPoolEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $poolId = PoolId::make('1');
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
        $liquidityPoolEntryBody = (new LiquidityPoolEntryBody())
            ->wrapLiquidityPoolEntryConstantProduct($liquidityPoolConstantProduct);
        $liquidityPoolEntry = (new LiquidityPoolEntry())
            ->withLiquidityPoolId($poolId)
            ->withBody($liquidityPoolEntryBody);
        $buffer = XDR::fresh()->write($liquidityPoolEntry);

        $this->assertEquals(
            'a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQAAAAAAAAAZAAAAAAAAADIAAAAAAAAASwAAAAAAAABkA==',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_pool_id_is_required_for_xdr_conversion()
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
            ->withTotalPoolShares(Int64::of(300))
            ->withPoolSharesTrustLineCount(Int64::of(400));
        $liquidityPoolEntryBody = (new LiquidityPoolEntryBody())
            ->wrapLiquidityPoolEntryConstantProduct($liquidityPoolConstantProduct);
        $liquidityPoolEntry = (new LiquidityPoolEntry())
            ->withBody($liquidityPoolEntryBody);
        XDR::fresh()->write($liquidityPoolEntry);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_body_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $poolId = PoolId::make('1');
        $liquidityPoolEntry = (new LiquidityPoolEntry())
            ->withLiquidityPoolId($poolId);
        XDR::fresh()->write($liquidityPoolEntry);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liquidityPoolEntry = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAUFCQ0QAAAAAVcAfh0fdvIam0MuNiicHVLrZZ0nBe95oEqB5396pelIAAAABQUJDRAAAAABVwB+HR928hqbQy42KJwdUutlnScF73mgSoHnf3ql6UgAAAGQAAAAAAAAAZAAAAAAAAADIAAAAAAAAASwAAAAAAAABkA==')
            ->read(LiquidityPoolEntry::class);

        $this->assertInstanceOf(LiquidityPoolEntry::class, $liquidityPoolEntry);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolEntry->getLiquidityPoolId());
        $this->assertInstanceOf(LiquidityPoolEntryBody::class, $liquidityPoolEntry->getBody());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $liquidityPoolEntry = (new LiquidityPoolEntry())
            ->withLiquidityPoolId(PoolId::make('1'));

        $this->assertInstanceOf(LiquidityPoolEntry::class, $liquidityPoolEntry);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolEntry->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::withBody
     * @covers ::getBody
     */
    public function it_accepts_a_body()
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
        $liquidityPoolEntryBody = (new LiquidityPoolEntryBody())
            ->wrapLiquidityPoolEntryConstantProduct($liquidityPoolConstantProduct);
        $liquidityPoolEntry = (new LiquidityPoolEntry())
            ->withBody($liquidityPoolEntryBody);

        $this->assertInstanceOf(LiquidityPoolEntry::class, $liquidityPoolEntry);
        $this->assertInstanceOf(LiquidityPoolEntryBody::class, $liquidityPoolEntry->getBody());
    }
}
