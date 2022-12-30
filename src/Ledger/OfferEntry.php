<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class OfferEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sellerId;
    protected Int64 $offerId;
    protected Asset $selling;
    protected Asset $buying;
    protected Int64 $amount; // the amount being sold
    protected Price $price;  // Price after fees. price=AmountBuying/AmountSelling=priceNumerator/priceDenominator
    protected UInt32 $flags;
    protected OfferEntryExt $ext;
    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sellerId)) {
            throw new InvalidArgumentException('The offer entry is missing an account Id');
        }

        if (!isset($this->offerId)) {
            throw new InvalidArgumentException('The offer entry is missing an offer Id');
        }

        if (!isset($this->selling)) {
            throw new InvalidArgumentException('The offer entry is missing a selling asset');
        }

        if (!isset($this->buying)) {
            throw new InvalidArgumentException('The offer entry is missing a buying asset');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The offer entry is missing an amount being sold');
        }

        if (!isset($this->price)) {
            throw new InvalidArgumentException('The offer entry is missing a price');
        }

        if (!isset($this->flags)) {
            $this->flags = UInt32::of(0);
        }

        if (!isset($this->ext)) {
            $this->ext = OfferEntryExt::empty();
        }

        $xdr->write($this->sellerId)
            ->write($this->offerId)
            ->write($this->selling)
            ->write($this->buying)
            ->write($this->amount)
            ->write($this->price)
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
        $offerEntry = new static();
        $offerEntry->sellerId = $xdr->read(AccountId::class);
        $offerEntry->offerId = $xdr->read(Int64::class);
        $offerEntry->selling = $xdr->read(Asset::class);
        $offerEntry->buying = $xdr->read(Asset::class);
        $offerEntry->amount = $xdr->read(Int64::class);
        $offerEntry->price = $xdr->read(Price::class);
        $offerEntry->flags = $xdr->read(UInt32::class);
        $offerEntry->ext = $xdr->read(OfferEntryExt::class);

        return $offerEntry;
    }

    /**
     * Get the seller account Id.
     *
     * @return AccountId
     */
    public function getSellerAccountId(): AccountId
    {
        return $this->sellerId;
    }

    /**
     * Accept an account Id.
     *
     * @param AccountId|Addressable|string $sellerId
     * @return static
     */
    public function withSellerAccountId(AccountId|Addressable|string $sellerId): static
    {
        if (is_string($sellerId)) {
            $sellerId = AccountId::fromAddressable($sellerId);
        } elseif ($sellerId instanceof Addressable) {
            $sellerId = AccountId::fromAddressable($sellerId->getAddress());
        }

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
     * Get the asset being sold.
     *
     * @return Asset
     */
    public function getSellingAsset(): Asset
    {
        return $this->selling;
    }

    /**
     * Accept an asset being sold.
     *
     * @param Asset $selling
     * @return static
     */
    public function withSellingAsset(Asset $selling): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->selling = Copy::deep($selling);

        return $clone;
    }

    /**
     * Get the asset being bought.
     *
     * @return Asset
     */
    public function getBuyingAsset(): Asset
    {
        return $this->buying;
    }

    /**
     * Accept an asset being bought.
     *
     * @param Asset $buying
     * @return static
     */
    public function withBuyingAsset(Asset $buying): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->buying = Copy::deep($buying);

        return $clone;
    }

    /**
     * Get the amount of assets being sold.
     *
     * @return Int64
     */
    public function getAmount(): Int64
    {
        return $this->amount;
    }

    /**
     * Accept an amount of assets being sold.
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
     * @param Price $price
     * @return static
     */
    public function withPrice(Price $price): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->price = Copy::deep($price);

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
     * Accept flags.
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
     * @return OfferEntryExt
     */
    public function getExtension(): OfferEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param OfferEntryExt $ext
     * @return static
     */
    public function withExtension(OfferEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
