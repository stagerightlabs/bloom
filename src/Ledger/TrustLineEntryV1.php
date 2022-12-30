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

final class TrustLineEntryV1 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Liabilities $liabilities;
    protected TrustLineEntryV1Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->liabilities)) {
            throw new InvalidArgumentException('The trust line entry v1 is missing liabilities');
        }

        if (!isset($this->ext)) {
            $this->ext = TrustLineEntryV1Ext::empty();
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
        $trustLineEntryV1 = new static();
        $trustLineEntryV1->liabilities = $xdr->read(Liabilities::class);
        $trustLineEntryV1->ext = $xdr->read(TrustLineEntryV1Ext::class);

        return $trustLineEntryV1;
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
        /** @var static */
        $clone = Copy::deep($this);
        $clone->liabilities = Copy::deep($liabilities);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TrustLineEntryV1Ext
     */
    public function getExtension(): TrustLineEntryV1Ext
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TrustLineEntryV1Ext $ext
     * @return static
     */
    public function withExtension(TrustLineEntryV1Ext $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
