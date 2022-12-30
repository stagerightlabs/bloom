<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\SCP\ScpQuorumSetList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpHistoryEntryV0 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ScpQuorumSetList $quorumSets;
    protected LedgerScpMessages $ledgerMessages;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->quorumSets)) {
            $this->quorumSets = ScpQuorumSetList::empty();
        }

        if (!isset($this->ledgerMessages)) {
            throw new InvalidArgumentException('The SCP history entry V0 is missing ledger messages');
        }

        $xdr->write($this->quorumSets)->write($this->ledgerMessages);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpHistoryEntryV0 = new static();
        $scpHistoryEntryV0->quorumSets = $xdr->read(ScpQuorumSetList::class);
        $scpHistoryEntryV0->ledgerMessages = $xdr->read(LedgerScpMessages::class);

        return $scpHistoryEntryV0;
    }

    /**
     * Get the list of quorum sets.
     *
     * @return ScpQuorumSetList
     */
    public function getQuorumSets(): ScpQuorumSetList
    {
        return $this->quorumSets;
    }

    /**
     * Accept a list of quorum sets.
     *
     * @param ScpQuorumSetList $quorumSets
     * @return static
     */
    public function withQuorumSets(ScpQuorumSetList $quorumSets): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->quorumSets = Copy::deep($quorumSets);

        return $clone;
    }

    /**
     * Get the ledger messages.
     *
     * @return LedgerScpMessages
     */
    public function getLedgerMessages(): LedgerScpMessages
    {
        return $this->ledgerMessages;
    }

    /**
     * Accept ledger messages.
     *
     * @param LedgerScpMessages $ledgerMessages
     * @return static
     */
    public function withLedgerMessages(LedgerScpMessages $ledgerMessages): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ledgerMessages = Copy::deep($ledgerMessages);

        return $clone;
    }
}
