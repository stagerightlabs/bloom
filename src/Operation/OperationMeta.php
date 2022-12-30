<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class OperationMeta implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LedgerEntryChanges $changes;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->changes)) {
            $this->changes = LedgerEntryChanges::empty();
        }

        $xdr->write($this->changes);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $operationMeta = new static();
        $operationMeta->changes = $xdr->read(LedgerEntryChanges::class);

        return $operationMeta;
    }

    /**
     * Get the list of ledger entry changes.
     *
     * @return LedgerEntryChanges
     */
    public function getChanges(): LedgerEntryChanges
    {
        return $this->changes;
    }

    /**
     * Accept a list of ledger entry changes.
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
