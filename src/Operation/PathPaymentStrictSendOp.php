<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\PathPaymentAssetList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class PathPaymentStrictSendOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $sendAsset; // the asset to pay with
    protected Int64 $sendAmount; // the amount of the sending asset to be delivered, excluding fees
    protected MuxedAccount $destination; // recipient of the payment
    protected Asset $destAsset; // the asset they will be paid with
    protected Int64 $destMin; // the minimum amount of the destination asset they will receive
    protected PathPaymentAssetList $path;  // additional conversion hops to be taken, if any

    /**
     * Create a new path-payment-strict-send operation.
     *
     * @param Asset|string $sendingAsset
     * @param Int64|ScaledAmount|integer|string $sendingAmount
     * @param MuxedAccount|AccountId|Addressable|string $destination
     * @param Asset|string $destinationAsset
     * @param Int64|ScaledAmount|integer|string $destinationMinimum
     * @param PathPaymentAssetList|array<Asset|string> $path
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Asset|string $sendingAsset,
        Int64|ScaledAmount|int|string $sendingAmount,
        MuxedAccount|AccountId|Addressable|string $destination,
        Asset|string $destinationAsset,
        Int64|ScaledAmount|int|string $destinationMinimum,
        PathPaymentAssetList|array $path = [],
        Addressable|string $source = null,
    ): Operation {
        $pathPaymentStrictSendOp = (new static())
            ->withSendingAsset($sendingAsset)
            ->withSendingAmount($sendingAmount)
            ->withDestination($destination)
            ->withDestinationAsset($destinationAsset)
            ->withDestinationMinimum($destinationMinimum)
            ->withPath($path);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::PATH_PAYMENT_STRICT_SEND, $pathPaymentStrictSendOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->sendAsset) && $this->sendAsset instanceof Asset)
            && (isset($this->sendAmount) && $this->sendAmount instanceof Int64)
            && (isset($this->destination) && $this->destination instanceof MuxedAccount)
            && (isset($this->destAsset) && $this->destAsset instanceof Asset)
            && (isset($this->destMin) && $this->destMin instanceof Int64);
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
        if (!isset($this->sendAsset)) {
            throw new InvalidArgumentException('The path payment strict receive operation is missing a send asset');
        }

        if (!isset($this->sendAmount)) {
            throw new InvalidArgumentException('The path payment strict receive operation is missing a send maximum');
        }

        if (!isset($this->destination)) {
            throw new InvalidArgumentException('The path payment strict receive operation is missing a destination');
        }

        if (!isset($this->destAsset)) {
            throw new InvalidArgumentException('The path payment strict receive operation is missing a destination asset');
        }

        if (!isset($this->destMin)) {
            throw new InvalidArgumentException('The path payment strict receive operation is missing a destination amount');
        }

        if (!isset($this->path)) {
            $this->path = PathPaymentAssetList::empty();
        }

        $xdr->write($this->sendAsset)
            ->write($this->sendAmount)
            ->write($this->destination)
            ->write($this->destAsset)
            ->write($this->destMin)
            ->write($this->path);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $pathPaymentStrictReceiveOp = new static();
        $pathPaymentStrictReceiveOp->sendAsset = $xdr->read(Asset::class);
        $pathPaymentStrictReceiveOp->sendAmount = $xdr->read(Int64::class);
        $pathPaymentStrictReceiveOp->destination = $xdr->read(MuxedAccount::class);
        $pathPaymentStrictReceiveOp->destAsset = $xdr->read(Asset::class);
        $pathPaymentStrictReceiveOp->destMin = $xdr->read(Int64::class);
        $pathPaymentStrictReceiveOp->path = $xdr->read(PathPaymentAssetList::class);

        return $pathPaymentStrictReceiveOp;
    }

    /**
     * Get the sending asset.
     *
     * @return Asset
     */
    public function getSendingAsset(): Asset
    {
        return $this->sendAsset;
    }

    /**
     * Accept a sending asset.
     *
     * @param Asset|string $sendAsset
     * @return static
     */
    public function withSendingAsset(Asset|string $sendAsset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sendAsset = is_string($sendAsset)
            ? Asset::fromNativeString($sendAsset)
            : Copy::deep($sendAsset);

        return $clone;
    }

    /**
     * Get the amount to be sent.
     *
     * @return Int64
     */
    public function getSendingAmount(): Int64
    {
        return $this->sendAmount;
    }

    /**
     * Accept an amount to be sent.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $sendAmount
     * @return static
     */
    public function withSendingAmount(Int64|ScaledAmount|int|string $sendAmount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sendAmount = Int64::normalize($sendAmount);

        return $clone;
    }

    /**
     * Get the destination.
     *
     * @return MuxedAccount
     */
    public function getDestination(): MuxedAccount
    {
        return $this->destination;
    }

    /**
     * Accept a destination.
     *
     * @param MuxedAccount|AccountId|Addressable|string $destination
     * @throws InvalidArgumentException
     * @return static
     */
    public function withDestination(MuxedAccount|AccountId|Addressable|string $destination): static
    {
        // account id
        if ($destination instanceof AccountId) {
            if (empty($destination->getAddress())) {
                throw new InvalidArgumentException('The provided account Id does not have an address');
            }

            $destination = MuxedAccount::fromAddressable($destination->getAddress());
        }

        // addressable
        if ($destination instanceof Addressable) {
            $destination = MuxedAccount::fromAddressable($destination->getAddress());
        }

        // string
        if (is_string($destination)) {
            $destination = MuxedAccount::fromAddressable($destination);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->destination = Copy::deep($destination);

        return $clone;
    }

    /**
     * Get the destination asset.
     *
     * @return Asset
     */
    public function getDestinationAsset(): Asset
    {
        return $this->destAsset;
    }

    /**
     * Accept a destination asset.
     *
     * @param Asset|string $destAsset
     * @return static
     */
    public function withDestinationAsset(Asset|string $destAsset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->destAsset = is_string($destAsset)
            ? Asset::fromNativeString($destAsset)
            : Copy::deep($destAsset);

        return $clone;
    }

    /**
     * Get the minimum destination amount.
     *
     * @return Int64
     */
    public function getDestinationMinimum(): Int64
    {
        return $this->destMin;
    }

    /**
     * Accept a minimum destination amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $destMin
     * @return static
     */
    public function withDestinationMinimum(Int64|ScaledAmount|int|string $destMin): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->destMin = Int64::normalize($destMin);

        return $clone;
    }

    /**
     * Get the the optional payment path.
     *
     * @return PathPaymentAssetList
     */
    public function getPath(): PathPaymentAssetList
    {
        return $this->path;
    }

    /**
     * Accept an optional payment path.
     *
     * @param PathPaymentAssetList|array<Asset|string> $path
     * @return static
     */
    public function withPath(PathPaymentAssetList|array $path): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->path = PathPaymentAssetList::normalize($path);

        return $clone;
    }
}
