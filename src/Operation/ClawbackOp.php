<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

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

final class ClawbackOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $asset;
    protected MuxedAccount $from;
    protected Int64 $amount;

    /**
     * Create a new clawback operation.
     *
     * @param MuxedAccount|Addressable|string $from
     * @param Asset|string $asset
     * @param Int64|ScaledAmount|integer|string $amount
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        MuxedAccount|Addressable|string $from,
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string $source = null,
    ): Operation {
        $clawbackOp = (new static())
            ->withTargetAccount($from)
            ->withAsset($asset)
            ->withAmount($amount);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CLAWBACK, $clawbackOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->asset) && $this->asset instanceof Asset)
            && (isset($this->from) && $this->from instanceof MuxedAccount)
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
        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The clawback operation is missing an asset');
        }

        if (!isset($this->from)) {
            throw new InvalidArgumentException('The clawback operation is missing a target account');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The clawback operation is missing an amount');
        }

        $xdr->write($this->asset)
            ->write($this->from)
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
        $clawbackOp = new static();
        $clawbackOp->asset = $xdr->read(Asset::class);
        $clawbackOp->from = $xdr->read(MuxedAccount::class);
        $clawbackOp->amount = $xdr->read(Int64::class);

        return $clawbackOp;
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
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->asset = is_string($asset)
            ? Asset::fromNativeString($asset)
            : Copy::deep($asset);

        return $clone;
    }

    /**
     * Get the target account.
     *
     * @return MuxedAccount
     */
    public function getTargetAccount(): MuxedAccount
    {
        return $this->from;
    }

    /**
     * Accept a target account.
     *
     * @param MuxedAccount|Addressable|string $from
     * @return static
     */
    public function withTargetAccount(MuxedAccount|Addressable|string $from): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->from = MuxedAccount::fromAddressable($from);

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
     * Accept an amount..
     *
     * @param Int64|ScaledAmount|int|string $amount
     * @return static
     */
    public function withAmount(Int64|ScaledAmount|int|string $amount): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->amount = Int64::normalize($amount);

        return $clone;
    }
}
