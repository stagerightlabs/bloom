<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\LiquidityPoolWithdrawOperationResource;
use StageRightLabs\Bloom\Horizon\ReservesResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\LiquidityPoolWithdrawOperationResource
 */
class LiquidityPoolWithdrawOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getLiquidityPoolId
     */
    public function it_returns_the_liquidity_pool_id()
    {
        $operation = LiquidityPoolWithdrawOperationResource::wrap(
            Response::fake('liquidity_pool_withdraw_operation')->getBody()
        );

        $this->assertEquals('abcdef', $operation->getLiquidityPoolId());
    }

    /**
     * @test
     * @covers ::getReservesMinimum
     */
    public function it_returns_the_reserves_minimum()
    {
        $operation = LiquidityPoolWithdrawOperationResource::wrap(
            Response::fake('liquidity_pool_withdraw_operation')->getBody()
        );

        foreach ($operation->getReservesMinimum() as $reserve) {
            $this->assertInstanceOf(ReservesResource::class, $reserve);
        }
    }

    /**
     * @test
     * @covers ::getShares
     */
    public function it_returns_the_share_amount()
    {
        $operation = LiquidityPoolWithdrawOperationResource::wrap(
            Response::fake('liquidity_pool_withdraw_operation')->getBody()
        );

        $this->assertEquals('1000.0000000', $operation->getShares()->toNativeString());
        $this->assertNull((new LiquidityPoolWithdrawOperationResource())->getShares());
    }

    /**
     * @test
     * @covers ::getReservesReceived
     */
    public function it_returns_the_reserves_received()
    {
        $operation = LiquidityPoolWithdrawOperationResource::wrap(
            Response::fake('liquidity_pool_withdraw_operation')->getBody()
        );

        foreach ($operation->getReservesReceived() as $reserve) {
            $this->assertInstanceOf(ReservesResource::class, $reserve);
        }
    }
}
