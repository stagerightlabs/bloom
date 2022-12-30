<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\SponsorshipDescriptorList;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExtensionV2 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $numSponsored;
    protected UInt32 $numSponsoring;
    protected SponsorshipDescriptorList $signerSponsoringIds;
    protected AccountEntryExtensionV2Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->numSponsored)) {
            $this->numSponsored = UInt32::of(0);
        }

        if (!isset($this->numSponsoring)) {
            $this->numSponsoring = UInt32::of(0);
        }

        if (!isset($this->signerSponsoringIds)) {
            $this->signerSponsoringIds = SponsorshipDescriptorList::empty();
        }

        if (!isset($this->ext)) {
            $this->ext = AccountEntryExtensionV2Ext::empty();
        }

        $xdr->write($this->numSponsored)
            ->write($this->numSponsoring)
            ->write($this->signerSponsoringIds)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $accountEntryExtensionV2 = new static();
        $accountEntryExtensionV2->numSponsored = $xdr->read(UInt32::class);
        $accountEntryExtensionV2->numSponsoring = $xdr->read(UInt32::class);
        $accountEntryExtensionV2->signerSponsoringIds = $xdr->read(SponsorshipDescriptorList::class);
        $accountEntryExtensionV2->ext = $xdr->read(AccountEntryExtensionV2Ext::class);

        return $accountEntryExtensionV2;
    }

    /**
     * Get the number sponsored.
     *
     * @return UInt32
     */
    public function getNumSponsored(): UInt32
    {
        return $this->numSponsored;
    }

    /**
     * Accept a number sponsored.
     *
     * @param UInt32 $numSponsored
     * @return static
     */
    public function withNumSponsored(UInt32 $numSponsored): static
    {
        $clone = Copy::deep($this);
        $clone->numSponsored = Copy::deep($numSponsored);

        return $clone;
    }

    /**
     * Get the number sponsoring.
     *
     * @return UInt32
     */
    public function getNumSponsoring(): UInt32
    {
        return $this->numSponsoring;
    }

    /**
     * Accept a number sponsoring.
     *
     * @param UInt32 $numSponsoring
     * @return static
     */
    public function withNumSponsoring(UInt32 $numSponsoring): static
    {
        $clone = Copy::deep($this);
        $clone->numSponsoring = Copy::deep($numSponsoring);

        return $clone;
    }

    /**
     * Get the signer sponsoring Ids.
     *
     * @return SponsorshipDescriptorList
     */
    public function getSignerSponsoringIds(): SponsorshipDescriptorList
    {
        return $this->signerSponsoringIds;
    }

    /**
     * Accept a list of signer sponsoring Ids.
     *
     * @param SponsorshipDescriptorList $signerSponsoringIds
     * @return static
     */
    public function withSignerSponsoringIds(SponsorshipDescriptorList $signerSponsoringIds): static
    {
        $clone = Copy::deep($this);
        $clone->signerSponsoringIds = Copy::deep($signerSponsoringIds);

        return $clone;
    }

    /**
     * Get the V2 extension.
     *
     * @return AccountEntryExtensionV2Ext
     */
    public function getExtension(): AccountEntryExtensionV2Ext
    {
        return $this->ext;
    }

    /**
     * Accept a V2 extension.
     *
     * @param AccountEntryExtensionV2Ext $ext
     * @return static
     */
    public function withExtension(AccountEntryExtensionV2Ext $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
