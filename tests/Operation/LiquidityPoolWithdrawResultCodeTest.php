<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResultCode
 */
class LiquidityPoolWithdrawResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS,
            -1 => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_MALFORMED,
            -2 => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_NO_TRUST,
            -3 => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED,
            -4 => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL,
            -5 => LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM,
        ];
        $liquidityPoolWithdrawResultCode = new LiquidityPoolWithdrawResultCode();

        $this->assertEquals($expected, $liquidityPoolWithdrawResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::success();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS, $liquidityPoolWithdrawResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::malformed();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_MALFORMED, $liquidityPoolWithdrawResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::noTrust();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_NO_TRUST, $liquidityPoolWithdrawResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::underfunded();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED, $liquidityPoolWithdrawResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::lineFull();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL, $liquidityPoolWithdrawResultCode->getType());
    }

    /**
     * @test
     * @covers ::underMinimum
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_under_minimum_type()
    {
        $liquidityPoolWithdrawResultCode = LiquidityPoolWithdrawResultCode::underMinimum();

        $this->assertInstanceOf(LiquidityPoolWithdrawResultCode::class, $liquidityPoolWithdrawResultCode);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM, $liquidityPoolWithdrawResultCode->getType());
    }
}
