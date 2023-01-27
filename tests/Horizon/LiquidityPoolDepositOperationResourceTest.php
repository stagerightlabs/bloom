<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\LiquidityPoolDepositOperationResource;
use StageRightLabs\Bloom\Horizon\ReservesResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\LiquidityPoolDepositOperationResource
 */
class LiquidityPoolDepositOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getLiquidityPoolId
     */
    public function it_returns_the_liquidity_pool_id()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals('abcdef', $operation->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::getReservesMaximum
     */
    public function it_returns_the_reserves_maximum()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        foreach ($operation->getReservesMaximum() as $reserve) {
            $this->assertInstanceOf(ReservesResource::class, $reserve);
        }
    }

    /**
     * @test
     * @covers ::getMinimumPriceString
     */
    public function it_returns_the_minimum_price_string()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals('0.2680000', $operation->getMinimumPriceString());
    }

    /**
     * @test
     * @covers ::getMinimumPriceNumerator
     */
    public function it_returns_the_minimum_price_numerator()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals(67, $operation->getMinimumPriceNumerator());
    }

    /**
     * @test
     * @covers ::getMinimumPriceDenominator
     */
    public function it_returns_the_minimum_price_denominator()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals(250, $operation->getMinimumPriceDenominator());
    }

    /**
     * @test
     * @covers ::getMaximumPriceString
     */
    public function it_returns_the_maximum_price_string()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals('0.3680000', $operation->getMaximumPriceString());
    }

    /**
     * @test
     * @covers ::getMaximumPriceNumerator
     */
    public function it_returns_the_maximum_price_numerator()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals(73, $operation->getMaximumPriceNumerator());
    }

    /**
     * @test
     * @covers ::getMaximumPriceDenominator
     */
    public function it_returns_the_maximum_price_denominator()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals(250, $operation->getMaximumPriceDenominator());
    }

    /**
     * @test
     * @covers ::getReservesDeposited
     */
    public function it_returns_the_reserves_deposited()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        foreach ($operation->getReservesDeposited() as $reserve) {
            $this->assertInstanceOf(ReservesResource::class, $reserve);
        }
    }

    /**
     * @test
     * @covers ::getSharesReceived
     */
    public function it_returns_the_shares_received()
    {
        $operation = LiquidityPoolDepositOperationResource::wrap(
            Response::fake('liquidity_pool_deposit_operation')->getBody()
        );

        $this->assertEquals('1000.0000000', $operation->getSharesReceived()->toNativeString());
        $this->assertNull((new LiquidityPoolDepositOperationResource())->getSharesReceived());
    }
}
