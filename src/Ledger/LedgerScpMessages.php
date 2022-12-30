<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\ScpEnvelopeList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerScpMessages implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerSeq;
    protected ScpEnvelopeList $messages;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerSeq)) {
            throw new InvalidArgumentException('The LedgerSCPMessages is missing a ledger seq');
        }

        if (!isset($this->messages)) {
            $this->messages = ScpEnvelopeList::empty();
        }

        $xdr->write($this->ledgerSeq)->write($this->messages);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerScpMessages = new static();
        $ledgerScpMessages->ledgerSeq = $xdr->read(UInt32::class);
        $ledgerScpMessages->messages = $xdr->read(ScpEnvelopeList::class);

        return $ledgerScpMessages;
    }

    /**
     * Get the ledger seq.
     *
     * @return UInt32
     */
    public function getLedgerSeq(): UInt32
    {
        return $this->ledgerSeq;
    }

    /**
     * Accept a ledger seq.
     *
     * @param UInt32|int $ledgerSeq
     * @return static
     */
    public function withLedgerSeq(UInt32|int $ledgerSeq): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ledgerSeq = is_int($ledgerSeq)
            ? UInt32::of($ledgerSeq)
            : Copy::deep($ledgerSeq);

        return $clone;
    }

    /**
     * Get the messages.
     *
     * @return ScpEnvelopeList
     */
    public function getMessages(): ScpEnvelopeList
    {
        return $this->messages;
    }

    /**
     * Accept a list of messages.
     *
     * @param ScpEnvelopeList $messages
     * @return static
     */
    public function withMessages(ScpEnvelopeList $messages): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->messages = Copy::deep($messages);

        return $clone;
    }
}
