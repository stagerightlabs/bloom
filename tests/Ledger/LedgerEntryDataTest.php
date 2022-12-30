<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\AccountEntry;
use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntry;
use StageRightLabs\Bloom\Ledger\DataEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntryData;
use StageRightLabs\Bloom\Ledger\LedgerEntryType;
use StageRightLabs\Bloom\Ledger\LiquidityPoolEntry;
use StageRightLabs\Bloom\Ledger\OfferEntry;
use StageRightLabs\Bloom\Ledger\TrustLineEntry;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryData
 */
class LedgerEntryDataTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LedgerEntryType::class, LedgerEntryData::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LedgerEntryType::ACCOUNT           => AccountEntry::class,
            LedgerEntryType::TRUSTLINE         => TrustLineEntry::class,
            LedgerEntryType::OFFER             => OfferEntry::class,
            LedgerEntryType::DATA              => DataEntry::class,
            LedgerEntryType::CLAIMABLE_BALANCE => ClaimableBalanceEntry::class,
            LedgerEntryType::LIQUIDITY_POOL    => LiquidityPoolEntry::class,
        ];

        $this->assertEquals($expected, LedgerEntryData::arms());
    }


    /**
     * @test
     * @covers ::wrapAccountEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry()
    {
        $accountEntry = new AccountEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapAccountEntry($accountEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(AccountEntry::class, $ledgerEntryData->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTrustLineEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_a_trust_line_entry()
    {
        $trustLineEntry = new TrustLineEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapTrustLineEntry($trustLineEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(TrustLineEntry::class, $ledgerEntryData->unwrap());
    }

    /**
     * @test
     * @covers ::wrapOfferEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_an_offer_entry()
    {
        $offerEntry = new OfferEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapOfferEntry($offerEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(OfferEntry::class, $ledgerEntryData->unwrap());
    }

    /**
     * @test
     * @covers ::wrapDataEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_a_data_entry()
    {
        $dataEntry = new DataEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapDataEntry($dataEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(DataEntry::class, $ledgerEntryData->unwrap());
    }

    /**
     * @test
     * @covers ::wrapClaimableBalanceEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_a_claimable_balance_entry()
    {
        $claimableBalanceEntry = new ClaimableBalanceEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapClaimableBalanceEntry($claimableBalanceEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(ClaimableBalanceEntry::class, $ledgerEntryData->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLiquidityPoolEntry
     * @covers ::unwrap
     */
    public function it_can_wrap_a_liquidity_pool_entry()
    {
        $liquidityPoolEntry = new LiquidityPoolEntry();
        $ledgerEntryData = (new LedgerEntryData())
            ->wrapLiquidityPoolEntry($liquidityPoolEntry);

        $this->assertInstanceOf(LedgerEntryData::class, $ledgerEntryData);
        $this->assertInstanceOf(LiquidityPoolEntry::class, $ledgerEntryData->unwrap());
    }
}
