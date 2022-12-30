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

final class BucketMetadata implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerVersion; // The protocol version used to create / merge this bucket
    protected BucketMetadataExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerVersion)) {
            throw new InvalidArgumentException('The bucket metadata is missing a ledger version');
        }

        if (!isset($this->ext)) {
            $this->ext = BucketMetadataExt::empty();
        }

        $xdr->write($this->ledgerVersion)->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $bucketMetadata = new static();
        $bucketMetadata->ledgerVersion = $xdr->read(UInt32::class);
        $bucketMetadata->ext = $xdr->read(BucketMetadataExt::class);

        return $bucketMetadata;
    }

    /**
     * Get the ledger version.
     *
     * @return UInt32
     */
    public function getLedgerVersion(): UInt32
    {
        return $this->ledgerVersion;
    }

    /**
     * Accept a ledger version.
     *
     * @param UInt32|int $ledgerVersion
     * @return static
     */
    public function withLedgerVersion(UInt32|int $ledgerVersion): static
    {
        if (is_int($ledgerVersion)) {
            $ledgerVersion = UInt32::of($ledgerVersion);
        }

        $clone = Copy::deep($this);
        $clone->ledgerVersion = Copy::deep($ledgerVersion);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return BucketMetadataExt
     */
    public function getExtension(): BucketMetadataExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param BucketMetadataExt $ext
     * @return static
     */
    public function withExtension(BucketMetadataExt $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
