<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\LiquidityPoolDepositResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolDepositResultCode
 */
class LiquidityPoolDepositResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS,
            -1 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_MALFORMED,
            -2 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NO_TRUST,
            -3 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED,
            -4 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED,
            -5 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL,
            -6 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE,
            -7 => LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_POOL_FULL,
        ];
        $liquidityPoolDepositResultCode = new LiquidityPoolDepositResultCode();

        $this->assertEquals($expected, $liquidityPoolDepositResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::success();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::malformed();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_MALFORMED, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::noTrust
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_no_trust_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::noTrust();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NO_TRUST, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::notAuthorized
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_authorized_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::notAuthorized();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::underfunded
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_underfunded_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::underfunded();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::lineFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_line_full_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::lineFull();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::badPrice
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_bad_price_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::badPrice();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE, $liquidityPoolDepositResultCode->getType());
    }

    /**
     * @test
     * @covers ::poolFull
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_pool_full_type()
    {
        $liquidityPoolDepositResultCode = LiquidityPoolDepositResultCode::poolFull();

        $this->assertInstanceOf(LiquidityPoolDepositResultCode::class, $liquidityPoolDepositResultCode);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_POOL_FULL, $liquidityPoolDepositResultCode->getType());
    }
}
