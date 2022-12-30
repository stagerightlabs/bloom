<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\StellarValue;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerHeader implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerVersion; // The protocol version of the ledger
    protected Hash $previousLedgerHash; // Hash of the previous ledger header
    protected StellarValue $scpValue; // What consensus agreed to.
    protected Hash $txSetResultHash; // The transaction result set that led to this ledger
    protected Hash $bucketListHash; // Hash of the ledger state
    protected UInt32 $ledgerSeq; // The sequence number of this ledger
    protected Int64 $totalCoins; // The total number of stroops in existence
    protected Int64 $feePool; // Fees burned since last inflation run
    protected UInt32 $inflationSeq; // Inflation sequence number
    protected UInt64 $idPool; // last used global ID, used for generating objects
    protected UInt32 $baseFee; // base fee per operation in stroops
    protected UInt32 $baseReserve; // Account base reserve in stroops
    protected UInt32 $maxTxSetSize; // maximum size a transaction set can be
    protected SkipList $skipList; // Hashes of ledgers in the past.
    protected LedgerHeaderExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerVersion)) {
            throw new InvalidArgumentException('The ledger header is missing a ledger version');
        }

        if (!isset($this->previousLedgerHash)) {
            throw new InvalidArgumentException('The ledger header is missing a previous ledger hash');
        }

        if (!isset($this->scpValue)) {
            throw new InvalidArgumentException('The ledger header is missing a SCP consensus value');
        }

        if (!isset($this->txSetResultHash)) {
            throw new InvalidArgumentException('The ledger header is missing a transaction set result hash');
        }

        if (!isset($this->bucketListHash)) {
            throw new InvalidArgumentException('The ledger header is missing a bucket list hash');
        }

        if (!isset($this->ledgerSeq)) {
            throw new InvalidArgumentException('The ledger header is missing a ledger sequence number');
        }

        if (!isset($this->totalCoins)) {
            throw new InvalidArgumentException('The ledger header is missing a total coins amount');
        }

        if (!isset($this->feePool)) {
            throw new InvalidArgumentException('The ledger header is missing a fee pool burn count');
        }

        if (!isset($this->inflationSeq)) {
            throw new InvalidArgumentException('The ledger header is missing an inflation sequence number');
        }

        if (!isset($this->idPool)) {
            throw new InvalidArgumentException('The ledger header is missing a last used global ID');
        }

        if (!isset($this->baseFee)) {
            throw new InvalidArgumentException('The ledger header is missing a base fee');
        }

        if (!isset($this->baseReserve)) {
            throw new InvalidArgumentException('The ledger header is missing a base reserve');
        }

        if (!isset($this->maxTxSetSize)) {
            throw new InvalidArgumentException('The ledger header is missing a maximum transaction set size');
        }

        if (!isset($this->skipList)) {
            throw new InvalidArgumentException('The ledger header is missing a skip list');
        }

        if (!isset($this->ext)) {
            $this->ext = LedgerHeaderExt::empty();
        }

        $xdr->write($this->ledgerVersion)
            ->write($this->previousLedgerHash)
            ->write($this->scpValue)
            ->write($this->txSetResultHash)
            ->write($this->bucketListHash)
            ->write($this->ledgerSeq)
            ->write($this->totalCoins)
            ->write($this->feePool)
            ->write($this->inflationSeq)
            ->write($this->idPool)
            ->write($this->baseFee)
            ->write($this->baseReserve)
            ->write($this->maxTxSetSize)
            ->write($this->skipList)
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
        $ledgerHeader = new static();
        $ledgerHeader->ledgerVersion = $xdr->read(UInt32::class);
        $ledgerHeader->previousLedgerHash = $xdr->read(Hash::class);
        $ledgerHeader->scpValue = $xdr->read(StellarValue::class);
        $ledgerHeader->txSetResultHash = $xdr->read(Hash::class);
        $ledgerHeader->bucketListHash = $xdr->read(Hash::class);
        $ledgerHeader->ledgerSeq = $xdr->read(UInt32::class);
        $ledgerHeader->totalCoins = $xdr->read(Int64::class);
        $ledgerHeader->feePool = $xdr->read(Int64::class);
        $ledgerHeader->inflationSeq = $xdr->read(UInt32::class);
        $ledgerHeader->idPool = $xdr->read(UInt64::class);
        $ledgerHeader->baseFee = $xdr->read(UInt32::class);
        $ledgerHeader->baseReserve = $xdr->read(UInt32::class);
        $ledgerHeader->maxTxSetSize = $xdr->read(UInt32::class);
        $ledgerHeader->skipList = $xdr->read(SkipList::class);
        $ledgerHeader->ext = $xdr->read(LedgerHeaderExt::class);

        return $ledgerHeader;
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
     * Get the previous ledger hash.
     *
     * @return Hash
     */
    public function getPreviousLedgerHash(): Hash
    {
        return $this->previousLedgerHash;
    }

    /**
     * Accept a previous ledger hash.
     *
     * @param Hash $previousLedgerHash
     * @return static
     */
    public function withPreviousLedgerHash(Hash $previousLedgerHash): static
    {
        $clone = Copy::deep($this);
        $clone->previousLedgerHash = Copy::deep($previousLedgerHash);

        return $clone;
    }

    /**
     * Get the SCP value.
     *
     * @return StellarValue
     */
    public function getSCPValue(): StellarValue
    {
        return $this->scpValue;
    }

    /**
     * Accept a SCP value.
     *
     * @param StellarValue $scpValue
     * @return static
     */
    public function withSCPValue(StellarValue $scpValue): static
    {
        $clone = Copy::deep($this);
        $clone->scpValue = Copy::deep($scpValue);

        return $clone;
    }

    /**
     * Get the TX set result hash.
     *
     * @return Hash
     */
    public function getTxSetResultHash(): Hash
    {
        return $this->txSetResultHash;
    }

    /**
     * Accept a TX set result hash.
     *
     * @param Hash $txSetResultHash
     * @return static
     */
    public function withTxSetResultHash(Hash $txSetResultHash): static
    {
        $clone = Copy::deep($this);
        $clone->txSetResultHash = Copy::deep($txSetResultHash);

        return $clone;
    }

    /**
     * Get the bucket list hash.
     *
     * @return Hash
     */
    public function getBucketListHash(): Hash
    {
        return $this->bucketListHash;
    }

    /**
     * Accept a bucket list hash.
     *
     * @param Hash $bucketListHash
     * @return static
     */
    public function withBucketListHash(Hash $bucketListHash): static
    {
        $clone = Copy::deep($this);
        $clone->bucketListHash = Copy::deep($bucketListHash);

        return $clone;
    }

    /**
     * Get the ledger sequence number.
     *
     * @return UInt32
     */
    public function getLedgerSeq(): UInt32
    {
        return $this->ledgerSeq;
    }

    /**
     * Accept a ledger sequence number.
     *
     * @param UInt32|int $ledgerSeq
     * @return static
     */
    public function withLedgerSeq(UInt32|int $ledgerSeq): static
    {
        if (is_int($ledgerSeq)) {
            $ledgerSeq = UInt32::of($ledgerSeq);
        }

        $clone = Copy::deep($this);
        $clone->ledgerSeq = Copy::deep($ledgerSeq);

        return $clone;
    }

    /**
     * Get the total coins count.
     *
     * @return Int64
     */
    public function getTotalCoins(): Int64
    {
        return $this->totalCoins;
    }

    /**
     * Accept a total coins count.
     *
     * @param Int64 $totalCoins
     * @return static
     */
    public function withTotalCoins(Int64 $totalCoins): static
    {
        $clone = Copy::deep($this);
        $clone->totalCoins = Copy::deep($totalCoins);

        return $clone;
    }

    /**
     * Get the fee pool.
     *
     * @return Int64
     */
    public function getFeePool(): Int64
    {
        return $this->feePool;
    }

    /**
     * Accept a fee pool.
     *
     * @param Int64 $feePool
     * @return static
     */
    public function withFeePool(Int64 $feePool): static
    {
        $clone = Copy::deep($this);
        $clone->feePool = Copy::deep($feePool);

        return $clone;
    }

    /**
     * Get the inflation sequence number.
     *
     * @return UInt32
     */
    public function getInflationSeq(): UInt32
    {
        return $this->inflationSeq;
    }

    /**
     * Accept an inflation sequence number.
     *
     * @param UInt32|int $inflationSeq
     * @return static
     */
    public function withInflationSeq(UInt32|int $inflationSeq): static
    {
        if (is_int($inflationSeq)) {
            $inflationSeq = UInt32::of($inflationSeq);
        }

        $clone = Copy::deep($this);
        $clone->inflationSeq = Copy::deep($inflationSeq);

        return $clone;
    }

    /**
     * Get the ID pool.
     *
     * @return UInt64
     */
    public function getIdPool(): UInt64
    {
        return $this->idPool;
    }

    /**
     * Accept an ID pool.
     *
     * @param UInt64 $idPool
     * @return static
     */
    public function withIdPool(UInt64 $idPool): static
    {
        $clone = Copy::deep($this);
        $clone->idPool = Copy::deep($idPool);

        return $clone;
    }

    /**
     * Get the base fee.
     *
     * @return UInt32
     */
    public function getBaseFee(): UInt32
    {
        return $this->baseFee;
    }

    /**
     * Accept a base fee.
     *
     * @param UInt32|int $baseFee
     * @return static
     */
    public function withBaseFee(UInt32|int $baseFee): static
    {
        if (is_int($baseFee)) {
            $baseFee = UInt32::of($baseFee);
        }

        $clone = Copy::deep($this);
        $clone->baseFee = Copy::deep($baseFee);

        return $clone;
    }

    /**
     * Get the base reserve.
     *
     * @return UInt32
     */
    public function getBaseReserve(): UInt32
    {
        return $this->baseReserve;
    }

    /**
     * Accept a base reserve.
     *
     * @param UInt32|int $baseReserve
     * @return static
     */
    public function withBaseReserve(UInt32|int $baseReserve): static
    {
        if (is_int($baseReserve)) {
            $baseReserve = UInt32::of($baseReserve);
        }

        $clone = Copy::deep($this);
        $clone->baseReserve = Copy::deep($baseReserve);

        return $clone;
    }

    /**
     * Get the max TX set size.
     *
     * @return UInt32
     */
    public function getMaxTxSetSize(): UInt32
    {
        return $this->maxTxSetSize;
    }

    /**
     * Accept a max TX set size.
     *
     * @param UInt32|int $maxTxSetSize
     * @return static
     */
    public function withMaxTxSetSize(UInt32|int $maxTxSetSize): static
    {
        if (is_int($maxTxSetSize)) {
            $maxTxSetSize = UInt32::of($maxTxSetSize);
        }

        $clone = Copy::deep($this);
        $clone->maxTxSetSize = Copy::deep($maxTxSetSize);

        return $clone;
    }

    /**
     * Get the skip list.
     *
     * @return SkipList
     */
    public function getSkipList(): SkipList
    {
        return $this->skipList;
    }

    /**
     * Accept a skip list.
     *
     * @param SkipList $skipList
     * @return static
     */
    public function withSkipList(SkipList $skipList): static
    {
        $clone = Copy::deep($this);
        $clone->skipList = Copy::deep($skipList);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return LedgerHeaderExt
     */
    public function getExtension(): LedgerHeaderExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param LedgerHeaderExt $ext
     * @return static
     */
    public function withExtension(LedgerHeaderExt $ext): static
    {
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
