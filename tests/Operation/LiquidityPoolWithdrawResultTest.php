<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResult;
use StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolWithdrawResult
 */
class LiquidityPoolWithdrawResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LiquidityPoolWithdrawResultCode::class, LiquidityPoolWithdrawResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS       => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_MALFORMED     => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_NO_TRUST      => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED   => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL     => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM => XDR::VOID,
        ];

        $this->assertEquals($expected, LiquidityPoolWithdrawResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_success_union()
    {
        $liquidityPoolWithdrawResult = LiquidityPoolWithdrawResult::success();
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS, $liquidityPoolWithdrawResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new LiquidityPoolWithdrawResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $liquidityPoolWithdrawResult = LiquidityPoolWithdrawResult::simulate(LiquidityPoolWithdrawResultCode::lineFull());

        $this->assertInstanceOf(LiquidityPoolWithdrawResult::class, $liquidityPoolWithdrawResult);
        $this->assertEquals(LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL, $liquidityPoolWithdrawResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $liquidityPoolWithdrawResultA = LiquidityPoolWithdrawResult::success();
        $liquidityPoolWithdrawResultB = new LiquidityPoolWithdrawResult();

        $this->assertTrue($liquidityPoolWithdrawResultA->wasSuccessful());
        $this->assertFalse($liquidityPoolWithdrawResultA->wasNotSuccessful());
        $this->assertTrue($liquidityPoolWithdrawResultB->wasNotSuccessful());
        $this->assertFalse($liquidityPoolWithdrawResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $liquidityPoolWithdrawResult = LiquidityPoolWithdrawResult::simulate(LiquidityPoolWithdrawResultCode::lineFull());

        $this->assertNotEmpty($liquidityPoolWithdrawResult->getErrorMessage());
        $this->assertEquals('liquidity_pool_withdraw_line_full', $liquidityPoolWithdrawResult->getErrorCode());
        $this->assertNull((new LiquidityPoolWithdrawResult())->getErrorMessage());
        $this->assertNull((new LiquidityPoolWithdrawResult())->getErrorCode());
    }
}
