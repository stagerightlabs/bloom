<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerUpgradeType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerUpgradeType
 */
class LedgerUpgradeTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => LedgerUpgradeType::LEDGER_UPGRADE_VERSION,
            2 => LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE,
            3 => LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE,
            4 => LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE,
            5 => LedgerUpgradeType::LEDGER_UPGRADE_FLAGS,
        ];
        $ledgerUpgradeType = new LedgerUpgradeType();

        $this->assertEquals($expected, $ledgerUpgradeType->getOptions());
    }

    /**
     * @test
     * @covers ::version
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_version_type()
    {
        $ledgerUpgradeType = LedgerUpgradeType::version();

        $this->assertInstanceOf(LedgerUpgradeType::class, $ledgerUpgradeType);
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_VERSION, $ledgerUpgradeType->getType());
    }

    /**
     * @test
     * @covers ::baseFee
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_base_fee_type()
    {
        $ledgerUpgradeType = LedgerUpgradeType::baseFee();

        $this->assertInstanceOf(LedgerUpgradeType::class, $ledgerUpgradeType);
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE, $ledgerUpgradeType->getType());
    }

    /**
     * @test
     * @covers ::maxTxSetSize
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_max_ts_set_size_type()
    {
        $ledgerUpgradeType = LedgerUpgradeType::maxTxSetSize();

        $this->assertInstanceOf(LedgerUpgradeType::class, $ledgerUpgradeType);
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE, $ledgerUpgradeType->getType());
    }

    /**
     * @test
     * @covers ::baseReserve
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_base_reserve_type()
    {
        $ledgerUpgradeType = LedgerUpgradeType::baseReserve();

        $this->assertInstanceOf(LedgerUpgradeType::class, $ledgerUpgradeType);
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE, $ledgerUpgradeType->getType());
    }

    /**
     * @test
     * @covers ::flags
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_flags_type()
    {
        $ledgerUpgradeType = LedgerUpgradeType::flags();

        $this->assertInstanceOf(LedgerUpgradeType::class, $ledgerUpgradeType);
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_FLAGS, $ledgerUpgradeType->getType());
    }
}
