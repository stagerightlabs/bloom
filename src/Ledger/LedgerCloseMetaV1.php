<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\Bloom\Transaction\TransactionResultMetaList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerCloseMetaV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerHeaderHistoryEntry $ledgerHeader;
    protected GeneralizedTransactionSet $transactionSet;
    protected TransactionResultMetaList $transactionsProcessing; // transactions are sorted in 'apply' order here; fees for all transactions are processed first, followed by applying transactions.
    protected UpgradeEntryMetaList $upgradesProcessing; // upgrades are applied last
    protected ScpHistoryEntryList $scpInfo; // other misc information attached to the ledger close

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerHeader)) {
            throw new InvalidArgumentException('The LedgerCloseMetaV1 is missing a ledger header');
        }

        if (!isset($this->transactionSet)) {
            throw new InvalidArgumentException('The LedgerCloseMetaV1 is missing a transaction set');
        }

        if (!isset($this->transactionsProcessing)) {
            $this->transactionsProcessing = TransactionResultMetaList::empty();
        }

        if (!isset($this->upgradesProcessing)) {
            $this->upgradesProcessing = UpgradeEntryMetaList::empty();
        }

        if (!isset($this->scpInfo)) {
            $this->scpInfo = ScpHistoryEntryList::empty();
        }

        $xdr->write($this->ledgerHeader)
            ->write($this->transactionSet)
            ->write($this->transactionsProcessing)
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
        $ledgerCloseMetaV1 = new static();
        $ledgerCloseMetaV1->ledgerHeader = $xdr->read(LedgerHeaderHistoryEntry::class);
        $ledgerCloseMetaV1->transactionSet = $xdr->read(GeneralizedTransactionSet::class);
        $ledgerCloseMetaV1->transactionsProcessing = $xdr->read(TransactionResultMetaList::class);
        $ledgerCloseMetaV1->upgradesProcessing = $xdr->read(UpgradeEntryMetaList::class);
        $ledgerCloseMetaV1->scpInfo = $xdr->read(ScpHistoryEntryList::class);

        return $ledgerCloseMetaV1;
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
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->ledgerHeader = Copy::deep($ledgerHeader);

        return $clone;
    }

    /**
     * Get the transaction set.
     *
     * @return GeneralizedTransactionSet
     */
    public function getTransactionSet(): GeneralizedTransactionSet
    {
        return $this->transactionSet;
    }

    /**
     * Accept a transaction set.
     *
     * @param GeneralizedTransactionSet $transactionSet
     * @return static
     */
    public function withTransactionSet(GeneralizedTransactionSet $transactionSet): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->transactionSet = Copy::deep($transactionSet);

        return $clone;
    }

    /**
     * Get the processing transactions.
     *
     * @return TransactionResultMetaList
     */
    public function getTransactionsProcessing(): TransactionResultMetaList
    {
        return $this->transactionsProcessing;
    }

    /**
     * Accept a list of processing transactions.
     *
     * @param TransactionResultMetaList $transactionsProcessing
     * @return static
     */
    public function withTransactionsProcessing(TransactionResultMetaList $transactionsProcessing): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->transactionsProcessing = Copy::deep($transactionsProcessing);

        return $clone;
    }

    /**
     * Get the processing upgrades.
     *
     * @return UpgradeEntryMetaList
     */
    public function getUpgradesProcessing(): UpgradeEntryMetaList
    {
        return $this->upgradesProcessing;
    }

    /**
     * Accept a list of processing upgrades.
     *
     * @param UpgradeEntryMetaList $upgradesProcessing
     * @return static
     */
    public function withUpgradesProcessing(UpgradeEntryMetaList $upgradesProcessing): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->upgradesProcessing = Copy::deep($upgradesProcessing);

        return $clone;
    }

    /**
     * Get the list of miscellaneous SCP information.
     *
     * @return ScpHistoryEntryList
     */
    public function getScpInfo(): ScpHistoryEntryList
    {
        return $this->scpInfo;
    }

    /**
     * Accept a list of miscellaneous SCP information.
     *
     * @param ScpHistoryEntryList $scpInfo
     * @return static
     */
    public function withScpInfo(ScpHistoryEntryList $scpInfo): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->scpInfo = Copy::deep($scpInfo);

        return $clone;
    }
}
