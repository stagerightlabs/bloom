<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\LiquidityPoolDepositResult;
use StageRightLabs\Bloom\Operation\LiquidityPoolDepositResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\LiquidityPoolDepositResult
 */
class LiquidityPoolDepositResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LiquidityPoolDepositResultCode::class, LiquidityPoolDepositResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS        => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_MALFORMED      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NO_TRUST       => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED    => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_POOL_FULL      => XDR::VOID,
        ];

        $this->assertEquals($expected, LiquidityPoolDepositResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_success_union()
    {
        $liquidityPoolDepositResult = LiquidityPoolDepositResult::success();
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS, $liquidityPoolDepositResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_when_no_value_is_set()
    {
        $this->assertNull((new LiquidityPoolDepositResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $liquidityPoolDepositResult = LiquidityPoolDepositResult::simulate(LiquidityPoolDepositResultCode::lineFull());

        $this->assertInstanceOf(LiquidityPoolDepositResult::class, $liquidityPoolDepositResult);
        $this->assertEquals(LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL, $liquidityPoolDepositResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $liquidityPoolDepositResultA = LiquidityPoolDepositResult::success();
        $liquidityPoolDepositResultB = new LiquidityPoolDepositResult();

        $this->assertTrue($liquidityPoolDepositResultA->wasSuccessful());
        $this->assertFalse($liquidityPoolDepositResultA->wasNotSuccessful());
        $this->assertTrue($liquidityPoolDepositResultB->wasNotSuccessful());
        $this->assertFalse($liquidityPoolDepositResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $liquidityPoolDepositResult = LiquidityPoolDepositResult::simulate(LiquidityPoolDepositResultCode::lineFull());

        $this->assertNotEmpty($liquidityPoolDepositResult->getErrorMessage());
        $this->assertEquals('liquidity_pool_deposit_line_full', $liquidityPoolDepositResult->getErrorCode());
        $this->assertNull((new LiquidityPoolDepositResult())->getErrorMessage());
        $this->assertNull((new LiquidityPoolDepositResult())->getErrorCode());
    }
}
