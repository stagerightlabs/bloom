<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TrustLineEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected TrustLineAsset $asset;
    protected Int64 $balance;
    protected Int64 $limit;
    protected UInt32 $flags;
    protected TrustLineEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The trust line entry is missing an account Id');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The trust line entry is missing an asset');
        }

        if (!isset($this->balance)) {
            throw new InvalidArgumentException('The trust line entry is missing a balance');
        }

        if (!isset($this->limit)) {
            $this->limit = Int64::of('9223372036854775807');
        }

        if (!isset($this->flags)) {
            throw new InvalidArgumentException('The trust line entry is missing flags');
        }

        if (!isset($this->ext)) {
            $this->ext = TrustLineEntryExt::empty();
        }

        $xdr->write($this->accountId)
            ->write($this->asset)
            ->write($this->balance)
            ->write($this->limit)
            ->write($this->flags)
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
        $trustLineEntry = new static();
        $trustLineEntry->accountId = $xdr->read(AccountId::class);
        $trustLineEntry->asset = $xdr->read(TrustLineAsset::class);
        $trustLineEntry->balance = $xdr->read(Int64::class);
        $trustLineEntry->limit = $xdr->read(Int64::class);
        $trustLineEntry->flags = $xdr->read(UInt32::class);
        $trustLineEntry->ext = $xdr->read(TrustLineEntryExt::class);

        return $trustLineEntry;
    }

    /**
     * Get the account Id.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    /**
     * Accept an account Id.
     *
     * @param AccountId|Addressable|string $accountId
     * @return static
     */
    public function withAccountId(AccountId|Addressable|string $accountId): static
    {
        if (is_string($accountId)) {
            $accountId = AccountId::fromAddressable($accountId);
        } elseif ($accountId instanceof Addressable) {
            $accountId = AccountId::fromAddressable($accountId->getAddress());
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = Copy::deep($accountId);

        return $clone;
    }

    /**
     * Get the asset.
     *
     * @return TrustLineAsset
     */
    public function getAsset(): TrustLineAsset
    {
        return $this->asset;
    }

    /**
     * Accept an asset.
     *
     * @param TrustLineAsset $asset
     * @return static
     */
    public function withAsset(TrustLineAsset $asset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->asset = Copy::deep($asset);

        return $clone;
    }

    /**
     * Get the balance.
     *
     * @return Int64
     */
    public function getBalance(): Int64
    {
        return $this->balance;
    }

    /**
     * Accept a balance.
     *
     * @param Int64|ScaledAmount|int|string $balance
     * @return static
     */
    public function withBalance(Int64|ScaledAmount|int|string $balance): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->balance = Int64::normalize($balance);

        return $clone;
    }

    /**
     * Get the limit.
     *
     * @return Int64
     */
    public function getLimit(): Int64
    {
        return $this->limit;
    }

    /**
     * Accept a limit.
     *
     * @param Int64|ScaledAmount|int|string $limit
     * @return static
     */
    public function withLimit(Int64|ScaledAmount|int|string $limit): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->limit = Int64::normalize($limit);

        return $clone;
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
     * @param UInt32 $flags
     * @return static
     */
    public function withFlags(UInt32 $flags): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->flags = Copy::deep($flags);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TrustLineEntryExt
     */
    public function getExtension(): TrustLineEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TrustLineEntryExt $ext
     * @return static
     */
    public function withExtension(TrustLineEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
