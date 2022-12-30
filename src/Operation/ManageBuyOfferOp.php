<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ManageBuyOfferOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $selling;
    protected Asset $buying;
    protected Int64 $buyAmount; // amount being bought. If set to 0, delete the offer
    protected Price $price; // price of the ting being sold in terms of what you are selling
    protected Int64 $offerId; // If set to 0 create a new offer, otherwise edit an existing offer

    /**
     * Create a new manage-buy-offer operation.
     *
     * @param Asset|string $sellingAsset
     * @param Asset|string $buyingAsset
     * @param Int64|ScaledAmount|integer|string $buyingAmount
     * @param Price|string $price
     * @param Int64|int|null $offerId
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Asset|string $sellingAsset,
        Asset|string $buyingAsset,
        Int64|ScaledAmount|int|string $buyingAmount,
        Price|string $price,
        Int64|int $offerId = null,
        Addressable|string $source = null,
    ): Operation {
        $manageBuyOfferOp = (new static())
            ->withSellingAsset($sellingAsset)
            ->withBuyingAsset($buyingAsset)
            ->withBuyingAmount($buyingAmount)
            ->withPrice($price)
            ->withOfferId($offerId);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::MANAGE_BUY_OFFER, $manageBuyOfferOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->selling) && $this->selling instanceof Asset)
            && (isset($this->buying) && $this->buying instanceof Asset)
            && (isset($this->buyAmount) && $this->buyAmount instanceof Int64)
            && (isset($this->price) && $this->price instanceof Price);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_MEDIUM;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->selling)) {
            throw new InvalidArgumentException('The manage buy offer operation is missing a selling asset');
        }

        if (!isset($this->buying)) {
            throw new InvalidArgumentException('The manage buy offer operation is missing a buying asset');
        }

        if (!isset($this->buyAmount)) {
            throw new InvalidArgumentException('The manage buy offer operation is missing a purchase quantity');
        }

        if (!isset($this->price)) {
            throw new InvalidArgumentException('The manage buy offer operation is missing a price');
        }

        if (!isset($this->offerId)) {
            $this->offerId = Int64::of(0);
        }

        $xdr->write($this->selling)
            ->write($this->buying)
            ->write($this->buyAmount)
            ->write($this->price)
            ->write($this->offerId);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $manageBuyOffer = new static();
        $manageBuyOffer->selling = $xdr->read(Asset::class);
        $manageBuyOffer->buying = $xdr->read(Asset::class);
        $manageBuyOffer->buyAmount = $xdr->read(Int64::class);
        $manageBuyOffer->price = $xdr->read(Price::class);
        $manageBuyOffer->offerId = $xdr->read(Int64::class);

        return $manageBuyOffer;
    }

    /**
     * Get the asset to be sold.
     *
     * @return Asset
     */
    public function getSellingAsset(): Asset
    {
        return $this->selling;
    }

    /**
     * Accept the asset to be sold.
     *
     * @param Asset|string $selling
     * @return static
     */
    public function withSellingAsset(Asset|string $selling): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->selling = is_string($selling)
            ? Asset::fromNativeString($selling)
            : Copy::deep($selling);

        return $clone;
    }

    /**
     * Get the asset to be bought.
     *
     * @return Asset
     */
    public function getBuyingAsset(): Asset
    {
        return $this->buying;
    }

    /**
     * Accept the asset to be bought.
     *
     * @param Asset|string $buying
     * @return static
     */
    public function withBuyingAsset(Asset|string $buying): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->buying = is_string($buying)
            ? Asset::fromNativeString($buying)
            : Copy::deep($buying);

        return $clone;
    }

    /**
     * Get the amount being sold.
     *
     * @return Int64
     */
    public function getBuyingAmount(): Int64
    {
        return $this->buyAmount;
    }

    /**
     * Accept an amount to be sold.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $buyAmount
     * @return static
     */
    public function withBuyingAmount(Int64|ScaledAmount|int|string $buyAmount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->buyAmount = Int64::normalize($buyAmount);

        return $clone;
    }

    /**
     * Get the price.
     *
     * @return Price
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    /**
     * Accept a price.
     *
     * @param Price|string $price
     * @return static
     */
    public function withPrice(Price|string $price): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->price = is_string($price)
            ? Price::fromNativeString($price)
            : Copy::deep($price);

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
     * @param Int64|int|null $offerId
     * @return static
     */
    public function withOfferId(Int64|int $offerId = null): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->offerId = is_null($offerId)
            ? Int64::of(0)
            : Int64::normalize($offerId);

        return $clone;
    }
}
