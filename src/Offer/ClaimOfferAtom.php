<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Offer;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClaimOfferAtom implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sellerId;
    protected Int64 $offerId;
    protected Asset $assetSold;
    protected Int64 $amountSold;
    protected Asset $assetBought;
    protected Int64 $amountBought;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sellerId)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing a seller Id');
        }

        if (!isset($this->offerId)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing an offer Id');
        }

        if (!isset($this->assetSold)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing the asset sold');
        }

        if (!isset($this->amountSold)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing the amount sold');
        }

        if (!isset($this->assetBought)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing the asset bought');
        }

        if (!isset($this->amountBought)) {
            throw new InvalidArgumentException('The ClaimOfferAtom is missing the amount');
        }

        $xdr->write($this->sellerId)
            ->write($this->offerId)
            ->write($this->assetSold)
            ->write($this->amountSold)
            ->write($this->assetBought)
            ->write($this->amountBought);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $claimOfferAtomV0 = new static();
        $claimOfferAtomV0->sellerId = $xdr->read(AccountId::class);
        $claimOfferAtomV0->offerId = $xdr->read(Int64::class);
        $claimOfferAtomV0->assetSold = $xdr->read(Asset::class);
        $claimOfferAtomV0->amountSold = $xdr->read(Int64::class);
        $claimOfferAtomV0->assetBought = $xdr->read(Asset::class);
        $claimOfferAtomV0->amountBought = $xdr->read(Int64::class);

        return $claimOfferAtomV0;
    }

    /**
     * Get the seller ed25519.
     *
     * @return AccountId
     */
    public function getSellerId(): AccountId
    {
        return $this->sellerId;
    }

    /**
     * Accept a seller ed25519.
     *
     * @param AccountId $sellerId
     * @return static
     */
    public function withSellerId(AccountId $sellerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sellerId = Copy::deep($sellerId);

        return $clone;
    }

    /**
     * Get the offer Id.
     *
     * @return Int64
     */
    public function getOfferId(): Int64
    {
        return $this->offerId;
    }

    /**
     * Accept an offer Id.
     *
     * @param Int64 $offerId
     * @return static
     */
    public function withOfferId(Int64 $offerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->offerId = Copy::deep($offerId);

        return $clone;
    }

    /**
     * Get the asset sold.
     *
     * @return Asset
     */
    public function getAssetSold(): Asset
    {
        return $this->assetSold;
    }

    /**
     * Accept an asset sold.
     *
     * @param Asset $assetSold
     * @return static
     */
    public function withAssetSold(Asset $assetSold): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->assetSold = Copy::deep($assetSold);

        return $clone;
    }

    /**
     * Get the amount sold.
     *
     * @return Int64
     */
    public function getAmountSold(): Int64
    {
        return $this->amountSold;
    }

    /**
     * Accept an amount sold.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $amountSold
     * @return static
     */
    public function withAmountSold(Int64|ScaledAmount|int|string $amountSold): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->amountSold = Int64::normalize($amountSold);

        return $clone;
    }

    /**
     * Get the asset bought.
     *
     * @return Asset
     */
    public function getAssetBought(): Asset
    {
        return $this->assetBought;
    }

    /**
     * Accept an asset bought.
     *
     * @param Asset $assetBought
     * @return static
     */
    public function withAssetBought(Asset $assetBought): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->assetBought = Copy::deep($assetBought);

        return $clone;
    }

    /**
     * Get the amount bought.
     *
     * @return Int64
     */
    public function getAmountBought(): Int64
    {
        return $this->amountBought;
    }

    /**
     * Accept an amount bought.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $amountBought
     * @return static
     */
    public function withAmountBought(Int64|ScaledAmount|int|string $amountBought): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->amountBought = Int64::normalize($amountBought);

        return $clone;
    }
}
