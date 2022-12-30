<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClaimableBalanceEntryExtensionV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $flags;
    protected ClaimableBalanceEntryExtensionV1Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->flags)) {
            $this->flags = UInt32::of(0);
        }

        if (!isset($this->ext)) {
            $this->ext = ClaimableBalanceEntryExtensionV1Ext::empty();
        }

        $xdr->write($this->ext)->write($this->flags);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $claimableBalanceEntryExtensionV1 = new static();
        $claimableBalanceEntryExtensionV1->ext = $xdr->read(ClaimableBalanceEntryExtensionV1Ext::class);
        $claimableBalanceEntryExtensionV1->flags = $xdr->read(UInt32::class);

        return $claimableBalanceEntryExtensionV1;
    }

    /**
     * Get the flags.
     *
     * @return UInt32
     */
    public function getFlags(): UInt32
    {
        return $this->flags;
    }

    /**
     * Accept flags.
     *
     * @param UInt32 $flags
     * @return static
     */
    public function withFlags(UInt32 $flags): static
    {
        $clone = Copy::deep($this);
        $clone->flags = Copy::deep($flags);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return ClaimableBalanceEntryExtensionV1Ext
     */
    public function getExtension(): ClaimableBalanceEntryExtensionV1Ext
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param ClaimableBalanceEntryExtensionV1Ext $ext
     * @return static
     */
    public function withExtension(ClaimableBalanceEntryExtensionV1Ext $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
