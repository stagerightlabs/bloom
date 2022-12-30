<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerScpMessages;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\ScpEnvelopeList;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerScpMessages
 */
class LedgerScpMessagesTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerScpMessages = (new LedgerSCPMessages())
            ->withLedgerSeq(UInt32::of(1));
        $buffer = XDR::fresh()->write($ledgerScpMessages);

        $this->assertEquals('AAAAAQAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_seq_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new LedgerSCPMessages());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerScpMessages = XDR::fromBase64('AAAAAQAAAAA=')
            ->read(LedgerScpMessages::class);

        $this->assertInstanceOf(LedgerSCPMessages::class, $ledgerScpMessages);
        $this->assertInstanceOf(UInt32::class, $ledgerScpMessages->getLedgerSeq());
        $this->assertInstanceOf(ScpEnvelopeList::class, $ledgerScpMessages->getMessages());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_uint32_as_a_ledger_seq()
    {
        $ledgerScpMessages = (new LedgerSCPMessages())
            ->withLedgerSeq(UInt32::of(1));

        $this->assertInstanceOf(LedgerScpMessages::class, $ledgerScpMessages);
        $this->assertInstanceOf(UInt32::class, $ledgerScpMessages->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withLedgerSeq
     * @covers ::getLedgerSeq
     */
    public function it_accepts_a_native_int_as_a_ledger_seq()
    {
        $ledgerScpMessages = (new LedgerSCPMessages())
            ->withLedgerSeq(1);

        $this->assertInstanceOf(LedgerScpMessages::class, $ledgerScpMessages);
        $this->assertInstanceOf(UInt32::class, $ledgerScpMessages->getLedgerSeq());
    }

    /**
     * @test
     * @covers ::withMessages
     * @covers ::getMessages
     */
    public function it_accepts_a_list_of_messages()
    {
        $ledgerScpMessages = (new LedgerSCPMessages())
            ->withMessages(ScpEnvelopeList::empty());

        $this->assertInstanceOf(LedgerScpMessages::class, $ledgerScpMessages);
        $this->assertInstanceOf(ScpEnvelopeList::class, $ledgerScpMessages->getMessages());
    }
}
