<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerScpMessages;
use StageRightLabs\Bloom\Ledger\ScpHistoryEntry;
use StageRightLabs\Bloom\Ledger\ScpHistoryEntryV0;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ScpHistoryEntry
 */
class ScpHistoryEntryTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, ScpHistoryEntry::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => ScpHistoryEntryV0::class,
        ];

        $this->assertEquals($expected, ScpHistoryEntry::arms());
    }

    /**
     * @test
     * @covers ::wrapScpHistoryEntryV0
     * @covers ::unwrap
     */
    public function it_can_wrap_a_scp_history_entry_v0()
    {
        $ledgerScpMessages = (new LedgerScpMessages())
            ->withLedgerSeq(UInt32::of(1));
        $scpHistoryEntryV0 = (new ScpHistoryEntryV0())
            ->withLedgerMessages($ledgerScpMessages);
        $scpHistoryEntry = ScpHistoryEntry::wrapScpHistoryEntryV0($scpHistoryEntryV0);

        $this->assertInstanceOf(ScpHistoryEntry::class, $scpHistoryEntry);
        $this->assertInstanceOf(ScpHistoryEntryV0::class, $scpHistoryEntry->unwrap());
    }
}
