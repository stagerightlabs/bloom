<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Ledger\DataEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntry;
use StageRightLabs\Bloom\Ledger\LedgerEntryChange;
use StageRightLabs\Bloom\Ledger\LedgerEntryChangeType;
use StageRightLabs\Bloom\Ledger\LedgerEntryData;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Ledger\LedgerKeyData;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryChange
 */
class LedgerEntryChangeTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(LedgerEntryChangeType::class, LedgerEntryChange::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            LedgerEntryChangeType::LEDGER_ENTRY_CREATED => LedgerEntry::class,
            LedgerEntryChangeType::LEDGER_ENTRY_UPDATED => LedgerEntry::class,
            LedgerEntryChangeType::LEDGER_ENTRY_REMOVED => LedgerKey::class,
            LedgerEntryChangeType::LEDGER_ENTRY_STATE   => LedgerEntry::class,
        ];

        $this->assertEquals($expected, LedgerEntryChange::arms());
    }

    /**
     * @test
     * @covers ::wrapCreatedLedgerEntry
     * @covers ::getType
     * @covers ::unwrap
     */
    public function it_can_wrap_a_created_ledger_entry()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $ledgerEntryChange = LedgerEntryChange::wrapCreatedLedgerEntry($ledgerEntry);

        $this->assertInstanceOf(LedgerEntryChange::class, $ledgerEntryChange);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_CREATED, $ledgerEntryChange->getType());
        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntryChange->unwrap());
    }

    /**
     * @test
     * @covers ::wrapUpdatedLedgerEntry
     * @covers ::getType
     * @covers ::unwrap
     */
    public function it_can_wrap_an_updated_ledger_entry()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $ledgerEntryChange = LedgerEntryChange::wrapUpdatedLedgerEntry($ledgerEntry);

        $this->assertInstanceOf(LedgerEntryChange::class, $ledgerEntryChange);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_UPDATED, $ledgerEntryChange->getType());
        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntryChange->unwrap());
    }

    /**
     * @test
     * @covers ::wrapRemovedLedgerKey
     * @covers ::getType
     * @covers ::unwrap
     */
    public function it_can_wrap_a_removed_ledger_entry()
    {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('example'));
        $ledgerKey = LedgerKey::wrapLedgerKeyData($ledgerKeyData);
        $ledgerEntryChange = LedgerEntryChange::wrapRemovedLedgerKey($ledgerKey);

        $this->assertInstanceOf(LedgerEntryChange::class, $ledgerEntryChange);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_REMOVED, $ledgerEntryChange->getType());
        $this->assertInstanceOf(LedgerKey::class, $ledgerEntryChange->unwrap());
    }

    /**
     * @test
     * @covers ::wrapStateLedgerEntry
     * @covers ::getType
     * @covers ::unwrap
     */
    public function it_can_wrap_a_state_ledger_entry()
    {
        $dataEntry = (new DataEntry())
            ->withAccountId(AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withDataName(String64::of('name'))
            ->withDataValue(DataValue::of('value'));
        $ledgerEntryData = LedgerEntryData::wrapDataEntry($dataEntry);
        $ledgerEntry = (new LedgerEntry())
            ->withLastModifiedLedgerSeq(UInt32::of(1))
            ->withData($ledgerEntryData);
        $ledgerEntryChange = LedgerEntryChange::wrapStateLedgerEntry($ledgerEntry);

        $this->assertInstanceOf(LedgerEntryChange::class, $ledgerEntryChange);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_STATE, $ledgerEntryChange->getType());
        $this->assertInstanceOf(LedgerEntry::class, $ledgerEntryChange->unwrap());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function the_type_is_null_if_no_value_is_set()
    {
        $this->assertNull((new LedgerEntryChange())->getType());
    }
}
