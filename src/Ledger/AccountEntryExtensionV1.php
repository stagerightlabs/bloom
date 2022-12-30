<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\Liabilities;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExtensionV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Liabilities $liabilities;
    protected AccountEntryExtensionV1Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->liabilities)) {
            throw new InvalidArgumentException("The account entry extension v1 is missing liabilities");
        }

        if (!isset($this->ext)) {
            $this->ext = AccountEntryExtensionV1Ext::empty();
        }

        $xdr->write($this->liabilities)->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $accountEntryExtensionV1 = new static();
        $accountEntryExtensionV1->liabilities = $xdr->read(Liabilities::class);
        $accountEntryExtensionV1->ext = $xdr->read(AccountEntryExtensionV1Ext::class);

        return $accountEntryExtensionV1;
    }

    /**
     * Get the liabilities.
     *
     * @return Liabilities
     */
    public function getLiabilities(): Liabilities
    {
        return $this->liabilities;
    }

    /**
     * Accept liabilities.
     *
     * @param Liabilities $liabilities
     * @return static
     */
    public function withLiabilities(Liabilities $liabilities): static
    {
        $clone = Copy::deep($this);
        $clone->liabilities = Copy::deep($liabilities);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return AccountEntryExtensionV1Ext
     */
    public function getExtension(): AccountEntryExtensionV1Ext
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param AccountEntryExtensionV1Ext $ext
     * @return static
     */
    public function withExtension(AccountEntryExtensionV1Ext $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
