<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class UpgradeEntryMeta implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerUpgrade $upgrade;
    protected LedgerEntryChanges $changes;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->upgrade)) {
            throw new InvalidArgumentException('The upgrade entry meta is missing upgrade data');
        }

        if (!isset($this->changes)) {
            $this->changes = LedgerEntryChanges::empty();
        }

        $xdr->write($this->upgrade)->write($this->changes);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $upgradeEntryMeta = new static();
        $upgradeEntryMeta->upgrade = $xdr->read(LedgerUpgrade::class);
        $upgradeEntryMeta->changes = $xdr->read(LedgerEntryChanges::class);

        return $upgradeEntryMeta;
    }

    /**
     * Get the upgrade.
     *
     * @return LedgerUpgrade
     */
    public function getUpgrade(): LedgerUpgrade
    {
        return $this->upgrade;
    }

    /**
     * Accept an upgrade.
     *
     * @param LedgerUpgrade $upgrade
     * @return static
     */
    public function withUpgrade(LedgerUpgrade $upgrade): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->upgrade = Copy::deep($upgrade);

        return $clone;
    }

    /**
     * Get the list of changes.
     *
     * @return LedgerEntryChanges
     */
    public function getChanges(): LedgerEntryChanges
    {
        return $this->changes;
    }

    /**
     * Accept a list of changes.
     *
     * @param LedgerEntryChanges $changes
     * @return static
     */
    public function withChanges(LedgerEntryChanges $changes): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->changes = Copy::deep($changes);

        return $clone;
    }
}
