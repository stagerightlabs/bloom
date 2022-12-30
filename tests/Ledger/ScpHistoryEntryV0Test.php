<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerScpMessages;
use StageRightLabs\Bloom\Ledger\ScpHistoryEntryV0;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\ScpQuorumSetList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ScpHistoryEntryV0
 */
class ScpHistoryEntryV0Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerScpMessages = (new LedgerScpMessages())
            ->withLedgerSeq(UInt32::of(1));
        $scpHistoryEntryV0 = (new ScpHistoryEntryV0())
            ->withLedgerMessages($ledgerScpMessages);
        $buffer = XDR::fresh()->write($scpHistoryEntryV0);

        $this->assertEquals('AAAAAAAAAAEAAAAA', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function ledger_messages_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ScpHistoryEntryV0());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $scpHistoryEntryV0 = XDR::fromBase64('AAAAAAAAAAEAAAAA')
            ->read(ScpHistoryEntryV0::class);

        $this->assertInstanceOf(ScpHistoryEntryV0::class, $scpHistoryEntryV0);
        $this->assertInstanceOf(ScpQuorumSetList::class, $scpHistoryEntryV0->getQuorumSets());
        $this->assertInstanceOf(LedgerSCPMessages::class, $scpHistoryEntryV0->getLedgerMessages());
    }

    /**
     * @test
     * @covers ::withQuorumSets
     * @covers ::getQuorumSets
     */
    public function it_accepts_a_list_of_quorum_sets()
    {
        $scpHistoryEntryV0 = (new ScpHistoryEntryV0())
            ->withQuorumSets(ScpQuorumSetList::empty());

        $this->assertInstanceOf(ScpHistoryEntryV0::class, $scpHistoryEntryV0);
        $this->assertInstanceOf(ScpQuorumSetList::class, $scpHistoryEntryV0->getQuorumSets());
    }

    /**
     * @test
     * @covers ::withLedgerMessages
     * @covers ::getLedgerMessages
     */
    public function it_accepts_ledger_messages()
    {
        $ledgerScpMessages = (new LedgerSCPMessages())
            ->withLedgerSeq(UInt32::of(1));
        $scpHistoryEntryV0 = (new ScpHistoryEntryV0())
            ->withLedgerMessages($ledgerScpMessages);

        $this->assertInstanceOf(ScpHistoryEntryV0::class, $scpHistoryEntryV0);
        $this->assertInstanceOf(LedgerSCPMessages::class, $scpHistoryEntryV0->getLedgerMessages());
    }
}
