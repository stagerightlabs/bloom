<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerEntryChangeType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryChangeType
 */
class LedgerEntryChangeTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => LedgerEntryChangeType::LEDGER_ENTRY_CREATED,
            1 => LedgerEntryChangeType::LEDGER_ENTRY_UPDATED,
            2 => LedgerEntryChangeType::LEDGER_ENTRY_REMOVED,
            3 => LedgerEntryChangeType::LEDGER_ENTRY_STATE,
        ];
        $ledgerEntryChangeType = new LedgerEntryChangeType();

        $this->assertEquals($expected, $ledgerEntryChangeType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $ledgerEntryChangeType = LedgerEntryChangeType::entryCreated();
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_CREATED, $ledgerEntryChangeType->getType());
    }

    /**
     * @test
     * @covers ::entryCreated
     */
    public function it_can_be_instantiated_as_a_ledger_entry_created_type()
    {
        $ledgerEntryChangeType = LedgerEntryChangeType::entryCreated();

        $this->assertInstanceOf(LedgerEntryChangeType::class, $ledgerEntryChangeType);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_CREATED, $ledgerEntryChangeType->getType());
    }

    /**
     * @test
     * @covers ::entryUpdated
     */
    public function it_can_be_instantiated_as_a_ledger_entry_updated_type()
    {
        $ledgerEntryChangeType = LedgerEntryChangeType::entryUpdated();

        $this->assertInstanceOf(LedgerEntryChangeType::class, $ledgerEntryChangeType);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_UPDATED, $ledgerEntryChangeType->getType());
    }

    /**
     * @test
     * @covers ::entryRemoved
     */
    public function it_can_be_instantiated_as_a_ledger_entry_removed_type()
    {
        $ledgerEntryChangeType = LedgerEntryChangeType::entryRemoved();

        $this->assertInstanceOf(LedgerEntryChangeType::class, $ledgerEntryChangeType);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_REMOVED, $ledgerEntryChangeType->getType());
    }

    /**
     * @test
     * @covers ::entryState
     */
    public function it_can_be_instantiated_as_a_ledger_entry_state_type()
    {
        $ledgerEntryChangeType = LedgerEntryChangeType::entryState();

        $this->assertInstanceOf(LedgerEntryChangeType::class, $ledgerEntryChangeType);
        $this->assertEquals(LedgerEntryChangeType::LEDGER_ENTRY_STATE, $ledgerEntryChangeType->getType());
    }
}
