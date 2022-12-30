<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerHeaderFlags;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerHeaderFlags
 */
class LedgerHeaderFlagsTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_TRADING_FLAG,
            2 => LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG,
            4 => LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG,
        ];
        $ledgerHeaderFlags = new LedgerHeaderFlags();

        $this->assertEquals($expected, $ledgerHeaderFlags->getOptions());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_an_integer_representation_of_the_selected_flag()
    {
        $this->assertEquals(1, LedgerHeaderFlags::disableLiquidityPoolTradingFlag()->toNativeInt());
        $this->assertEquals(2, LedgerHeaderFlags::disableLiquidityPoolDepositFlag()->toNativeInt());
        $this->assertEquals(4, LedgerHeaderFlags::disableLiquidityPoolWithdrawalFlag()->toNativeInt());
    }

    /**
     * @test
     * @covers ::disableLiquidityPoolTradingFlag
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_trading_flag()
    {
        $claimantType = LedgerHeaderFlags::disableLiquidityPoolTradingFlag();

        $this->assertInstanceOf(LedgerHeaderFlags::class, $claimantType);
        $this->assertEquals(LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_TRADING_FLAG, $claimantType->getType());
    }

    /**
     * @test
     * @covers ::disableLiquidityPoolDepositFlag
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_deposit_flag()
    {
        $claimantType = LedgerHeaderFlags::disableLiquidityPoolDepositFlag();

        $this->assertInstanceOf(LedgerHeaderFlags::class, $claimantType);
        $this->assertEquals(LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG, $claimantType->getType());
    }

    /**
     * @test
     * @covers ::disableLiquidityPoolWithdrawalFlag
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_withdrawal_flag()
    {
        $claimantType = LedgerHeaderFlags::disableLiquidityPoolWithdrawalFlag();

        $this->assertInstanceOf(LedgerHeaderFlags::class, $claimantType);
        $this->assertEquals(LedgerHeaderFlags::DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG, $claimantType->getType());
    }
}
