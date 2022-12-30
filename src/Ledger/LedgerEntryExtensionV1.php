<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\SponsorshipDescriptor;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerEntryExtensionV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected SponsorshipDescriptor $sponsoringId;
    protected LedgerEntryExtensionV1Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sponsoringId)) {
            $this->sponsoringId = SponsorshipDescriptor::none();
        }

        if (!isset($this->ext)) {
            $this->ext = LedgerEntryExtensionV1Ext::empty();
        }

        $xdr->write($this->sponsoringId)->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerEntryExtensionV1 = new static();
        $ledgerEntryExtensionV1->sponsoringId = $xdr->read(SponsorshipDescriptor::class);
        $ledgerEntryExtensionV1->ext = $xdr->read(LedgerEntryExtensionV1Ext::class);

        return $ledgerEntryExtensionV1;
    }

    /**
     * Get the sponsoring Id.
     *
     * @return SponsorshipDescriptor
     */
    public function getSponsoringId(): SponsorshipDescriptor
    {
        return $this->sponsoringId;
    }

    /**
     * Accept a sponsoring Id.
     *
     * @param SponsorshipDescriptor $sponsoringId
     * @return static
     */
    public function withSponsoringId(SponsorshipDescriptor $sponsoringId): static
    {
        $clone = Copy::deep($this);
        $clone->sponsoringId = Copy::deep($sponsoringId);

        return $clone;
    }

    /**
     * Get the ledger entry extension v1 ext.
     *
     * @return LedgerEntryExtensionV1Ext
     */
    public function getExtension(): LedgerEntryExtensionV1Ext
    {
        return $this->ext;
    }

    /**
     * Accept a ledger entry extension v1 ext.
     *
     * @param LedgerEntryExtensionV1Ext $ext
     * @return static
     */
    public function withExtension(LedgerEntryExtensionV1Ext $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
