<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerHeaderExtensionV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $flags;
    protected LedgerHeaderExtensionV1Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->flags)) {
            throw new InvalidArgumentException('The ledger header extension v1 is missing flags');
        }

        if (!isset($this->ext)) {
            $this->ext = LedgerHeaderExtensionV1Ext::empty();
        }

        $xdr->write($this->flags)->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerHeaderExtensionV1 = new static();
        $ledgerHeaderExtensionV1->flags = $xdr->read(UInt32::class);
        $ledgerHeaderExtensionV1->ext = $xdr->read(LedgerHeaderExtensionV1Ext::class);

        return $ledgerHeaderExtensionV1;
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
     * Accept a set of flags.
     *
     * @param UInt32|int $flags
     * @return static
     */
    public function withFlags(UInt32|int $flags): static
    {
        if (is_int($flags)) {
            $flags = UInt32::of($flags);
        }

        $clone = Copy::deep($this);
        $clone->flags = Copy::deep($flags);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return LedgerHeaderExtensionV1Ext
     */
    public function getExtension(): LedgerHeaderExtensionV1Ext
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param LedgerHeaderExtensionV1Ext $ext
     * @return static
     */
    public function withExtension(LedgerHeaderExtensionV1Ext $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
