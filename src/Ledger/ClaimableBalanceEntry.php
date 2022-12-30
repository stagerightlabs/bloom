<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClaimableBalanceEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimableBalanceId $balanceId; // Unique identifier for this ClaimableBalanceEntry
    protected ClaimantList $claimants; // List of claimants with associated predicate, limit 10
    protected Asset $asset; // Any asset including native
    protected Int64 $amount; // Amount of asset
    protected ClaimableBalanceEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->balanceId)) {
            throw new InvalidArgumentException('The claimable balance entry is missing an Id');
        }

        if (!isset($this->claimants)) {
            throw new InvalidArgumentException('The claimable balance entry is missing a list of claimants');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The claimable balance entry is missing an asset');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The claimable balance entry is missing an amount');
        }

        if (!isset($this->ext)) {
            $this->ext = ClaimableBalanceEntryExt::empty();
        }

        $xdr->write($this->balanceId)
            ->write($this->claimants)
            ->write($this->asset)
            ->write($this->amount)
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
        $claimableBalanceEntry = new static();
        $claimableBalanceEntry->balanceId = $xdr->read(ClaimableBalanceId::class);
        $claimableBalanceEntry->claimants = $xdr->read(ClaimantList::class);
        $claimableBalanceEntry->asset = $xdr->read(Asset::class);
        $claimableBalanceEntry->amount = $xdr->read(Int64::class);
        $claimableBalanceEntry->ext = $xdr->read(ClaimableBalanceEntryExt::class);

        return $claimableBalanceEntry;
    }

    /**
     * Get the claimable balance Id.
     *
     * @return ClaimableBalanceId
     */
    public function getClaimableBalanceId(): ClaimableBalanceId
    {
        return $this->balanceId;
    }

    /**
     * Accept a claimable balance Id.
     *
     * @param ClaimableBalanceId $balanceId
     * @return static
     */
    public function withClaimableBalanceId(ClaimableBalanceId $balanceId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->balanceId = Copy::deep($balanceId);

        return $clone;
    }

    /**
     * Get the list of claimants.
     *
     * @return ClaimantList
     */
    public function getClaimants(): ClaimantList
    {
        return $this->claimants;
    }

    /**
     * Accept a list of claimants.
     *
     * @param ClaimantList $claimants
     * @return static
     */
    public function withClaimants(ClaimantList $claimants): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->claimants = Copy::deep($claimants);

        return $clone;
    }

    /**
     * Get the asset.
     *
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * Accept an asset.
     *
     * @param Asset $asset
     * @return static
     */
    public function withAsset(Asset $asset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->asset = Copy::deep($asset);

        return $clone;
    }

    /**
     * Get the amount.
     *
     * @return Int64
     */
    public function getAmount(): Int64
    {
        return $this->amount;
    }

    /**
     * Accept an amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $amount
     * @return static
     */
    public function withAmount(Int64|ScaledAmount|int|string $amount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->amount = Int64::normalize($amount);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return ClaimableBalanceEntryExt
     */
    public function getExtension(): ClaimableBalanceEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param ClaimableBalanceEntryExt $ext
     * @return static
     */
    public function withExtension(ClaimableBalanceEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
