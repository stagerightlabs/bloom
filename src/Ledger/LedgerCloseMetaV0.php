<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Transaction\TransactionResultMetaList;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerCloseMetaV0 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerHeaderHistoryEntry $ledgerHeader;
    protected TransactionSet $txSet; // Sorted in "hash order"
    protected TransactionResultMetaList $txProcessing; // Sorted in "apply" order. fees for all transactions are processed first followed by applying transactions
    protected UpgradeEntryMetaList $upgradesProcessing; // Upgrades are applied last
    protected ScpHistoryEntryList $scpInfo; // Other misc information attached tot he ledger close

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerHeader)) {
            throw new InvalidArgumentException('The ledger close meta v0 is missing a ledger header');
        }

        if (!isset($this->txSet)) {
            throw new InvalidArgumentException('The ledger close meta v0 is missing a transaction set');
        }

        if (!isset($this->txProcessing)) {
            throw new InvalidArgumentException('The ledger close meta v0 is missing a transaction processing set');
        }

        if (!isset($this->upgradesProcessing)) {
            $this->upgradesProcessing = UpgradeEntryMetaList::empty();
        }

        if (!isset($this->scpInfo)) {
            $this->scpInfo = ScpHistoryEntryList::empty();
        }

        $xdr->write($this->ledgerHeader)
            ->write($this->txSet)
            ->write($this->txProcessing)
            ->write($this->upgradesProcessing)
            ->write($this->scpInfo);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerCloseMetaV0 = new static();
        $ledgerCloseMetaV0->ledgerHeader = $xdr->read(LedgerHeaderHistoryEntry::class);
        $ledgerCloseMetaV0->txSet = $xdr->read(TransactionSet::class);
        $ledgerCloseMetaV0->txProcessing = $xdr->read(TransactionResultMetaList::class);
        $ledgerCloseMetaV0->upgradesProcessing = $xdr->read(UpgradeEntryMetaList::class);
        $ledgerCloseMetaV0->scpInfo = $xdr->read(ScpHistoryEntryList::class);

        return $ledgerCloseMetaV0;
    }

    /**
     * Get the ledger header.
     *
     * @return LedgerHeaderHistoryEntry
     */
    public function getLedgerHeader(): LedgerHeaderHistoryEntry
    {
        return $this->ledgerHeader;
    }

    /**
     * Accept a ledger header.
     *
     * @param LedgerHeaderHistoryEntry $ledgerHeader
     * @return static
     */
    public function withLedgerHeader(LedgerHeaderHistoryEntry $ledgerHeader): static
    {
        $clone = Copy::deep($this);
        $clone->ledgerHeader = Copy::deep($ledgerHeader);

        return $clone;
    }

    /**
     * Get the transaction set, sorted in "hash" order.
     *
     * @return TransactionSet
     */
    public function getTransactionSet(): TransactionSet
    {
        return $this->txSet;
    }

    /**
     * Accept a transaction set.
     *
     * @param TransactionSet $txSet
     * @return static
     */
    public function withTransactionSet(TransactionSet $txSet): static
    {
        $clone = Copy::deep($this);
        $clone->txSet = Copy::deep($txSet);

        return $clone;
    }

    /**
     * Get the list of transaction processing details.
     *
     * @return TransactionResultMetaList
     */
    public function getTransactionProcessing(): TransactionResultMetaList
    {
        return $this->txProcessing;
    }

    /**
     * Accept a list of transaction processing details.
     *
     * @param TransactionResultMetaList $txProcessing
     * @return static
     */
    public function withTransactionProcessing(TransactionResultMetaList $txProcessing): static
    {
        $clone = Copy::deep($this);
        $clone->txProcessing = Copy::deep($txProcessing);

        return $clone;
    }

    /**
     * Get the list of upgrade details.
     *
     * @return UpgradeEntryMetaList
     */
    public function getUpgradesProcessing(): UpgradeEntryMetaList
    {
        return $this->upgradesProcessing;
    }

    /**
     * Accept a list of upgrade details.
     *
     * @param UpgradeEntryMetaList $upgradesProcessing
     * @return static
     */
    public function withUpgradesProcessing(UpgradeEntryMetaList $upgradesProcessing): static
    {
        $clone = Copy::deep($this);
        $clone->upgradesProcessing = Copy::deep($upgradesProcessing);

        return $clone;
    }

    /**
     * Get the list miscellaneous information attached to the ledger, if any.
     *
     * @return ScpHistoryEntryList
     */
    public function getScpInfo(): ScpHistoryEntryList
    {
        return $this->scpInfo;
    }

    /**
     * Accept a list of miscellaneous information attached to the ledger.
     *
     * @param ScpHistoryEntryList $scpInfo
     * @return static
     */
    public function withScpInfo(ScpHistoryEntryList $scpInfo): static
    {
        $clone = Copy::deep($this);
        $clone->scpInfo = Copy::deep($scpInfo);

        return $clone;
    }
}
