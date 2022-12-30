<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Operation\LiquidityPoolDepositOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolDepositOp
 */
class LiquidityPoolDepositOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = LiquidityPoolDepositOp::operation(
            poolId: PoolId::make('1'),
            maxAmountA: '10',
            maxAmountB: '8',
            minPrice: '2/1',
            maxPrice: '3/1',
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $liquidityPoolDepositOp = new LiquidityPoolDepositOp();
        $this->assertFalse($liquidityPoolDepositOp->isReady());

        $liquidityPoolDepositOp = $liquidityPoolDepositOp->withLiquidityPoolId(PoolId::make('1'));
        $this->assertFalse($liquidityPoolDepositOp->isReady());

        $liquidityPoolDepositOp = $liquidityPoolDepositOp->withMaxAmountA('10');
        $this->assertFalse($liquidityPoolDepositOp->isReady());

        $liquidityPoolDepositOp = $liquidityPoolDepositOp->withMaxAmountB('8');
        $this->assertFalse($liquidityPoolDepositOp->isReady());

        $liquidityPoolDepositOp = $liquidityPoolDepositOp->withMinPrice('2/1');
        $this->assertFalse($liquidityPoolDepositOp->isReady());

        $liquidityPoolDepositOp = $liquidityPoolDepositOp->withMaxPrice('3/1');
        $this->assertTrue($liquidityPoolDepositOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new LiquidityPoolDepositOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId(PoolId::make('1'))
            ->withMaxAmountA(1)
            ->withMaxAmountB(1)
            ->withMinPrice(Price::of(1, 2))
            ->withMaxPrice(Price::of(1, 2));
        $buffer = XDR::fresh()->write($liquidityPoolDepositOp);

        $this->assertEquals('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAABAAAAAQAAAAIAAAABAAAAAg==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_pool_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMaxAmountA(1)
            ->withMaxAmountB(1)
            ->withMinPrice(Price::of(1, 2))
            ->withMaxPrice(Price::of(1, 2));
        XDR::fresh()->write($liquidityPoolDepositOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_max_amount_a_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId('1')
            ->withMaxAmountB(1)
            ->withMinPrice(Price::of(1, 2))
            ->withMaxPrice(Price::of(1, 2));
        XDR::fresh()->write($liquidityPoolDepositOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_max_amount_b_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId('1')
            ->withMaxAmountA(1)
            ->withMinPrice(Price::of(1, 2))
            ->withMaxPrice(Price::of(1, 2));
        XDR::fresh()->write($liquidityPoolDepositOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_min_price_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId('1')
            ->withMaxAmountA(1)
            ->withMaxAmountB(1)
            ->withMaxPrice(Price::of(1, 2));
        XDR::fresh()->write($liquidityPoolDepositOp);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_max_price_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId('1')
            ->withMaxAmountA(1)
            ->withMaxAmountB(1)
            ->withMinPrice(Price::of(1, 2));
        XDR::fresh()->write($liquidityPoolDepositOp);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $liquidityPoolDepositOp = XDR::fromBase64('a4ayc/80/OGda4BO/1o/V0etpOqiLx1JwB5S3beHW0sAAAAAAAAAAQAAAAAAAAABAAAAAQAAAAIAAAABAAAAAg==')
            ->read(LiquidityPoolDepositOp::class);

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolDepositOp->getLiquidityPoolId());
        $this->assertInstanceOf(Int64::class, $liquidityPoolDepositOp->getMaxAmountA());
        $this->assertInstanceOf(Int64::class, $liquidityPoolDepositOp->getMaxAmountB());
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMinPrice());
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMaxPrice());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolId
     * @covers ::getLiquidityPoolId
     */
    public function it_accepts_a_liquidity_pool_id()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withLiquidityPoolId(PoolId::make('1'));

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(PoolId::class, $liquidityPoolDepositOp->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::withMaxAmountA
     * @covers ::getMaxAmountA
     */
    public function it_accepts_a_max_amount_a()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMaxAmountA(1);

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Int64::class, $liquidityPoolDepositOp->getMaxAmountA());
    }

    /**
     * @test
     * @covers ::withMaxAmountB
     * @covers ::getMaxAmountB
     */
    public function it_accepts_a_max_amount_b()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMaxAmountB(1);

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Int64::class, $liquidityPoolDepositOp->getMaxAmountB());
    }

    /**
     * @test
     * @covers ::withMinPrice
     * @covers ::getMinPrice
     */
    public function it_accepts_a_min_price()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMinPrice(Price::of(1, 2));

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMinPrice());
    }

    /**
     * @test
     * @covers ::withMinPrice
     * @covers ::getMinPrice
     */
    public function it_accepts_a_min_price_string()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMinPrice('1/2');

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMinPrice());
    }

    /**
     * @test
     * @covers ::withMaxPrice
     * @covers ::getMaxPrice
     */
    public function it_accepts_a_max_price()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMaxPrice(Price::of(1, 2));

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMaxPrice());
    }

    /**
     * @test
     * @covers ::withMaxPrice
     * @covers ::getMaxPrice
     */
    public function it_accepts_a_max_price_string()
    {
        $liquidityPoolDepositOp = (new LiquidityPoolDepositOp())
            ->withMaxPrice('1/2');

        $this->assertInstanceOf(LiquidityPoolDepositOp::class, $liquidityPoolDepositOp);
        $this->assertInstanceOf(Price::class, $liquidityPoolDepositOp->getMaxPrice());
    }
}
