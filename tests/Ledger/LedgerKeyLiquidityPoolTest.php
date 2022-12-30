<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyLiquidityPool;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyLiquidityPool
 */
class LedgerKeyLiquidityPoolTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerKeyLiquidityPool = (new LedgerKeyLiquidityPool())
            ->withLiquidityPoolId(PoolId::make('1'));
        $buffer = XDR::fresh()->write($ledgerKeyLiquidityPool);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0s=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_liquidity_pool_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new LedgerKeyLiquidityPool());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyLiquidityPool = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0s=')
            ->read(LedgerKeyLiquidityPool::class);

        $this->assertInstanceOf(LedgerKeyLiquidityPool::class, $ledgerKeyLiquidityPool);
        $this->assertInstanceOf(PoolId::class, $ledgerKeyLiquidityPool->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $ledgerKeyLiquidityPool = (new LedgerKeyLiquidityPool())
            ->withLiquidityPoolId(PoolId::make('1'));

        $this->assertInstanceOf(LedgerKeyLiquidityPool::class, $ledgerKeyLiquidityPool);
        $this->assertInstanceOf(PoolId::class, $ledgerKeyLiquidityPool->getLiquidityPoolId());
    }
}
