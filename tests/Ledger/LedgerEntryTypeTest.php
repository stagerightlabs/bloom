<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerEntryType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryType
 */
class LedgerEntryTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => LedgerEntryType::ACCOUNT,
            1 => LedgerEntryType::TRUSTLINE,
            2 => LedgerEntryType::OFFER,
            3 => LedgerEntryType::DATA,
            4 => LedgerEntryType::CLAIMABLE_BALANCE,
            5 => LedgerEntryType::LIQUIDITY_POOL,
        ];
        $ledgerEntryType = new LedgerEntryType();

        $this->assertEquals($expected, $ledgerEntryType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $ledgerEntryType = LedgerEntryType::account();
        $this->assertEquals(LedgerEntryType::ACCOUNT, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::account
     */
    public function it_can_be_instantiated_as_an_account_type()
    {
        $ledgerEntryType = LedgerEntryType::account();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::ACCOUNT, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::trustline
     */
    public function it_can_be_instantiated_as_a_trustline_type()
    {
        $ledgerEntryType = LedgerEntryType::trustline();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::TRUSTLINE, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::offer
     */
    public function it_can_be_instantiated_as_an_offer_type()
    {
        $ledgerEntryType = LedgerEntryType::offer();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::OFFER, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::data
     */
    public function it_can_be_instantiated_as_a_data_type()
    {
        $ledgerEntryType = LedgerEntryType::data();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::DATA, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::claimableBalance
     */
    public function it_can_be_instantiated_as_a_claimable_balance_type()
    {
        $ledgerEntryType = LedgerEntryType::claimableBalance();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::CLAIMABLE_BALANCE, $ledgerEntryType->getType());
    }

    /**
     * @test
     * @covers ::liquidityPool
     */
    public function it_can_be_instantiated_as_a_liquidity_pool_type()
    {
        $ledgerEntryType = LedgerEntryType::liquidityPool();

        $this->assertInstanceOf(LedgerEntryType::class, $ledgerEntryType);
        $this->assertEquals(LedgerEntryType::LIQUIDITY_POOL, $ledgerEntryType->getType());
    }
}
