<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerHeaderHistoryEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $hash;
    protected LedgerHeader $header;
    protected LedgerHeaderHistoryEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->hash)) {
            throw new InvalidArgumentException('The ledger header history entry is missing a hash');
        }

        if (!isset($this->header)) {
            throw new InvalidArgumentException('The ledger header history entry is missing a header');
        }

        if (!isset($this->ext)) {
            $this->ext = LedgerHeaderHistoryEntryExt::empty();
        }

        $xdr->write($this->hash)
            ->write($this->header)
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
        $ledgerHeaderHistoryEntry = new static();
        $ledgerHeaderHistoryEntry->hash = $xdr->read(Hash::class);
        $ledgerHeaderHistoryEntry->header = $xdr->read(LedgerHeader::class);
        $ledgerHeaderHistoryEntry->ext = $xdr->read(LedgerHeaderHistoryEntryExt::class);

        return $ledgerHeaderHistoryEntry;
    }

    /**
     * Get the hash.
     *
     * @return Hash
     */
    public function getHash(): Hash
    {
        return $this->hash;
    }

    /**
     * Accept a hash.
     *
     * @param Hash $hash
     * @return static
     */
    public function withHash(Hash $hash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->hash = Copy::deep($hash);

        return $clone;
    }

    /**
     * Get the ledger header.
     *
     * @return LedgerHeader
     */
    public function getHeader(): LedgerHeader
    {
        return $this->header;
    }

    /**
     * Accept a ledger header.
     *
     * @param LedgerHeader $header
     * @return static
     */
    public function withHeader(LedgerHeader $header): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->header = Copy::deep($header);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return LedgerHeaderHistoryEntryExt
     */
    public function getExtension(): LedgerHeaderHistoryEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param LedgerHeaderHistoryEntryExt $ext
     * @return static
     */
    public function withExtension(LedgerHeaderHistoryEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
