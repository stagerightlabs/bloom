<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawOp
 */
class LiquidityPoolWithdrawOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = LiquidityPoolWithdrawOp::operation(
            poolId: PoolId::make('1'),
            amount: '10',
            minAmountA: '6',
            minAmountB: '4',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $liquidityPoolWithdrawOp = new LiquidityPoolWithdrawOp();
        $this->assertFalse($liquidityPoolWithdrawOp->isReady());

        $liquidityPoolWithdrawOp = $liquidityPoolWithdrawOp->withLiquidityPoolId(PoolId::make('1'));
        $this->assertFalse($liquidityPoolWithdrawOp->isReady());

        $liquidityPoolWithdrawOp = $liquidityPoolWithdrawOp->withAmount('10');
        $this->assertFalse($liquidityPoolWithdrawOp->isReady());

        $liquidityPoolWithdrawOp = $liquidityPoolWithdrawOp->withMinAmountA('6');
        $this->assertFalse($liquidityPoolWithdrawOp->isReady());

        $liquidityPoolWithdrawOp = $liquidityPoolWithdrawOp->withMinAmountB('4');
        $this->assertTrue($liquidityPoolWithdrawOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new LiquidityPoolWithdrawOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAmount(Int64::of(1))
            ->withMinAmountA(Int64::of(2))
            ->withMinAmountB(Int64::of(3));
        $buffer = XDR::fresh()->write($liquidityPoolWithdrawOp);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAACAAAAAAAAAAM=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_liquidity_pool_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withAmount(Int64::of(1))
            ->withMinAmountA(Int64::of(2))
            ->withMinAmountB(Int64::of(3));
        XDR::fresh()->write($liquidityPoolWithdrawOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_amount_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withMinAmountA(Int64::of(2))
            ->withMinAmountB(Int64::of(3));
        XDR::fresh()->write($liquidityPoolWithdrawOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_minimum_amount_a_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAmount(Int64::of(1))
            ->withMinAmountB(Int64::of(3));
        XDR::fresh()->write($liquidityPoolWithdrawOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_minimum_amount_b_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withAmount(Int64::of(1))
            ->withMinAmountA(Int64::of(2));
        XDR::fresh()->write($liquidityPoolWithdrawOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liquidityPoolWithdrawOp = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAACAAAAAAAAAAM=')
            ->read(LiquidityPoolWithdrawOp::class);

        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $liquidityPoolWithdrawOp);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolWithdrawOp->getLiquidityPoolId());
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getAmount());
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getMinAmountA());
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getMinAmountB());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withLiquidityPoolId('1');

        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $liquidityPoolWithdrawOp);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolWithdrawOp->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::withAmount
     * @covers ::getAmount
     */
    public function it_accepts_an_amount()
    {
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withAmount(1);

        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $liquidityPoolWithdrawOp);
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getAmount());
    }

    /**
     * @test
     * @covers ::withMinAmountA
     * @covers ::getMinAmountA
     */
    public function it_accepts_a_min_amount_a()
    {
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withMinAmountA(1);

        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $liquidityPoolWithdrawOp);
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getMinAmountA());
    }

    /**
     * @test
     * @covers ::withMinAmountB
     * @covers ::getMinAmountB
     */
    public function it_accepts_a_min_amount_b()
    {
        $liquidityPoolWithdrawOp = (new LiquidityPoolWithdrawOp())
            ->withMinAmountB(1);

        $this->assertInstanceOf(LiquidityPoolWithdrawOp::class, $liquidityPoolWithdrawOp);
        $this->assertInstanceOf(Int64::class, $liquidityPoolWithdrawOp->getMinAmountB());
    }
}
