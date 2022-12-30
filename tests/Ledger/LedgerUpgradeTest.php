<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerUpgrade;
use StageRightLabs\Bloom\Ledger\LedgerUpgradeType;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerUpgrade
 */
class LedgerUpgradeTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LedgerUpgradeType::class, LedgerUpgrade::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LedgerUpgradeType::LEDGER_UPGRADE_VERSION         => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE        => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE    => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_FLAGS           => UInt32::class,
        ];

        $this->assertEquals($expected, LedgerUpgrade::arms());
    }

    /**
     * @test
     * @covers ::wrapVersion
     */
    public function it_can_wrap_a_uint32_version()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapVersion(UInt32::of(1));

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
    }

    /**
     * @test
     * @covers ::wrapVersion
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_version()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapVersion(1);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_VERSION, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapBaseFee
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_uint32_base_fee()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapBaseFee(UInt32::of(2));

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE, $ledgerUpgrade->getType());
    }
    /**
     * @test
     * @covers ::wrapBaseFee
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_base_fee()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapBaseFee(2);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapMaxTxSetSize
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_uint32_max_tx_set_size()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapMaxTxSetSize(3);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapMaxTxSetSize
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_max_tx_set_size()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapMaxTxSetSize(3);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapBaseReserve
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_uint32_base_reserve()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapBaseReserve(UInt32::of(4));

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapBaseReserve
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_base_reserve()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapBaseReserve(4);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapFlags
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_uint32_as_a_set_of_flags()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapFlags(UInt32::of(5));

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_FLAGS, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::wrapFlags
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_as_a_set_of_flags()
    {
        $ledgerUpgrade = LedgerUpgrade::wrapFlags(5);

        $this->assertInstanceOf(LedgerUpgrade::class, $ledgerUpgrade);
        $this->assertInstanceOf(UInt32::class, $ledgerUpgrade->unwrap());
        $this->assertEquals(LedgerUpgradeType::LEDGER_UPGRADE_FLAGS, $ledgerUpgrade->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_a_null_type_when_no_value_is_set()
    {
        $this->assertNull((new LedgerUpgrade())->getType());
    }
}
