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

final class CreatePassiveSellOfferOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $selling; // Asset A
    protected Asset $buying; // Asset B
    protected Int64 $amount; // The amount being purchased
    protected Price $price; // Cost of A in terms of B

    /**
     * Create a new create-passive-sell-offer operation.
     *
     * @param Asset|string $sellingAsset
     * @param Asset|string $buyingAsset
     * @param Price|string $price
     * @param Int64|ScaledAmount|integer|string $amount
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Asset|string $sellingAsset,
        Asset|string $buyingAsset,
        Price|string $price,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string $source = null,
    ): Operation {
        $createPassiveSellOfferOp = (new static())
            ->withSellingAsset($sellingAsset)
            ->withBuyingAsset($buyingAsset)
            ->withAmount($amount)
            ->withPrice($price);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CREATE_PASSIVE_SELL_OFFER, $createPassiveSellOfferOp))
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
            && (isset($this->amount) && $this->amount instanceof Int64)
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
            throw new InvalidArgumentException('The create passive sell offer operation is missing the asset to be sold');
        }

        if (!isset($this->buying)) {
            throw new InvalidArgumentException('The create passive sell offer operation is missing the asset to be bought');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The create passive sell offer operation is missing the amount to be purchased');
        }

        if (!isset($this->price)) {
            throw new InvalidArgumentException('The create passive sell offer operation is missing a price');
        }

        $xdr->write($this->selling)
            ->write($this->buying)
            ->write($this->amount)
            ->write($this->price);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $createPassiveSellOfferOp = new static();
        $createPassiveSellOfferOp->selling = $xdr->read(Asset::class);
        $createPassiveSellOfferOp->buying = $xdr->read(Asset::class);
        $createPassiveSellOfferOp->amount = $xdr->read(Int64::class);
        $createPassiveSellOfferOp->price = $xdr->read(Price::class);

        return $createPassiveSellOfferOp;
    }

    /**
     * Get the asset being sold.
     *
     * @return Asset
     */
    public function getSellingAsset(): Asset
    {
        return $this->selling;
    }

    /**
     * Accept an asset to be sold.
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
     * Accept an asset to be bought.
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
     * Get the amount to be sold.
     *
     * @return Int64
     */
    public function getAmount(): Int64
    {
        return $this->amount;
    }

    /**
     * Accept an amount to be sold.
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
}
