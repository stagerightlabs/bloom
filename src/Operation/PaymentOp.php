<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class PaymentOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected MuxedAccount $destination;
    protected Asset $asset;
    protected Int64 $amount;

    /**
     * Create a new payment operation
     *
     * @param Addressable|string $destination
     * @param Asset|string $asset
     * @param Int64|ScaledAmount|int|string $amount
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Addressable|string $destination,
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string $source = null,
    ): Operation {
        $paymentOp = (new static())
            ->withDestination($destination)
            ->withAsset($asset)
            ->withAmount($amount);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::PAYMENT, $paymentOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->destination) && $this->destination instanceof MuxedAccount)
            && (isset($this->asset) && $this->asset instanceof Asset)
            && (isset($this->amount) && $this->amount instanceof Int64);
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
        if (!isset($this->destination)) {
            throw new InvalidArgumentException('The payment operation is missing a destination');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The payment operation is missing an asset');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The payment operation is missing an amount');
        }

        $xdr->write($this->destination)
            ->write($this->asset)
            ->write($this->amount);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $paymentOp = new static();
        $paymentOp->destination = $xdr->read(MuxedAccount::class);
        $paymentOp->asset = $xdr->read(Asset::class);
        $paymentOp->amount = $xdr->read(Int64::class);

        return $paymentOp;
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
     * @param Asset|string $asset
     * @return static
     */
    public function withAsset(Asset|string $asset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->asset = is_string($asset)
            ? Asset::fromNativeString($asset)
            : Copy::deep($asset);

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
}
