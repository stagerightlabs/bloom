<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Asset\AlphaNum4;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Offer\ClaimLiquidityAtom;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimLiquidityAtom
 */
class ClaimLiquidityAtomTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        $buffer = XDR::fresh()->write($claimLiquidityAtom);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAABQUJDRAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAADAAAAAUVGR0gAAAAAqgavSSyGch8fQ4g2hZSAA4tp2nH/PwRMeKLkjJWcVQMAAAAAAAAABA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_a_liquidity_pool_id_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimLiquidityAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimLiquidityAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_sold_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimLiquidityAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_asset_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAmountBought(Int64::of(4));
        XDR::fresh()->write($claimLiquidityAtom);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_requires_an_amount_bought_to_be_converted_to_xdr()
    {
        $this->expectException(InvalidArgumentException::class);
        $claimLiquidityAtom = (new ClaimLiquidityAtom())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAssetSold(Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')))
            ->withAmountSold(Int64::of(3))
            ->withAssetBought(Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ')));
        XDR::fresh()->write($claimLiquidityAtom);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimLiquidityAtom = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAABQUJDRAAAAACqBq9JLIZyHx9DiDaFlIADi2nacf8/BEx4ouSMlZxVAwAAAAAAAAADAAAAAUVGR0gAAAAAqgavSSyGch8fQ4g2hZSAA4tp2nH/PwRMeKLkjJWcVQMAAAAAAAAABA==')
            ->read(ClaimLiquidityAtom::class);

        $this->assertInstanceOf(ClaimLiquidityAtom::class, $claimLiquidityAtom);
        $this->assertEquals(PoolId::make('1')->toNativeString(), $claimLiquidityAtom->getLiquidityPoolId()->toNativeString());
        $this->assertTrue($claimLiquidityAtom->getAmountSold()->isEqualTo(3));
        $this->assertTrue($claimLiquidityAtom->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withLiquidityPoolId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withLiquidityPoolId(PoolId::make('1'));

        $this->assertInstanceOf(PoolId::class, $claimLiquidityAtom->getLiquidityPoolId());
        $this->assertEquals(
            PoolId::make('1')->toNativeString(),
            $claimLiquidityAtom->getLiquidityPoolId()->toNativeString()
        );
    }

    /**
     * @test
     * @covers ::withAssetSold
     * @covers ::getAssetSold
     */
    public function it_accepts_an_asset_sold()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAssetSold(
            Asset::wrapAlphaNum4(AlphaNum4::of('ABCD', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimLiquidityAtom->getAssetSold());
        $this->assertEquals('ABCD', $claimLiquidityAtom->getAssetSold()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_an_int64_as_an_amount_sold()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAmountSold(Int64::of(3));

        $this->assertInstanceOf(Int64::class, $claimLiquidityAtom->getAmountSold());
        $this->assertTrue($claimLiquidityAtom->getAmountSold()->isEqualTo(3));
    }

    /**
     * @test
     * @covers ::withAmountSold
     * @covers ::getAmountSold
     */
    public function it_accepts_a_scaled_amount_as_an_amount_sold()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAmountSold(ScaledAmount::of(3));

        $this->assertInstanceOf(Int64::class, $claimLiquidityAtom->getAmountSold());
        $this->assertEquals('30000000', $claimLiquidityAtom->getAmountSold()->toNativeString());
    }

    /**
     * @test
     * @covers ::withAssetBought
     * @covers ::getAssetBought
     */
    public function it_accepts_an_asset_bought()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAssetBought(
            Asset::wrapAlphaNum4(AlphaNum4::of('EFGH', 'GCVANL2JFSDHEHY7IOEDNBMUQABYW2O2OH7T6BCMPCROJDEVTRKQGPSJ'))
        );

        $this->assertInstanceOf(Asset::class, $claimLiquidityAtom->getAssetBought());
        $this->assertEquals('EFGH', $claimLiquidityAtom->getAssetBought()->unwrap()->getAssetCode());
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_an_int64_as_an_amount_bought()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAmountBought(Int64::of(4));

        $this->assertInstanceOf(Int64::class, $claimLiquidityAtom->getAmountBought());
        $this->assertTrue($claimLiquidityAtom->getAmountBought()->isEqualTo(4));
    }

    /**
     * @test
     * @covers ::withAmountBought
     * @covers ::getAmountBought
     */
    public function it_accepts_a_scaled_amount_as_an_amount_bought()
    {
        $claimLiquidityAtom = (new ClaimLiquidityAtom())->withAmountBought(ScaledAmount::of(4));

        $this->assertInstanceOf(Int64::class, $claimLiquidityAtom->getAmountBought());
        $this->assertEquals('40000000', $claimLiquidityAtom->getAmountBought()->toNativeString());
    }
}
