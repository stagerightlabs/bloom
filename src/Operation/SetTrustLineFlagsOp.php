<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\TrustLineFlags;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SetTrustLineFlagsOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $trustor;
    protected Asset $asset;
    protected UInt32 $clearFlags; // which flags to clear
    protected UInt32 $setFlags; // which flags to set

    /**
     * Create a new set-trustline-flags operation.
     *
     * @param AccountId|Addressable|string $trustor
     * @param Asset|string $asset
     * @param boolean|null $authorized
     * @param boolean|null $authorizedToMaintainLiabilities
     * @param boolean|null $clawbackEnabled
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        AccountId|Addressable|string $trustor,
        Asset|string $asset,
        bool $authorized = null,
        bool $authorizedToMaintainLiabilities = null,
        bool $clawbackEnabled = null,
        Addressable|string $source = null,
    ): Operation {
        $setFlags = 0;
        $clearFlags = 0;

        // Set the 'authorized' flag
        if ($authorized === true) {
            $setFlags |= TrustLineFlags::authorized()->toNativeInt();
        } elseif ($authorized === false) {
            $clearFlags |= TrustLineFlags::authorized()->toNativeInt();
        }

        // Set the 'authorized to maintain liabilities' flag
        if ($authorizedToMaintainLiabilities === true) {
            $setFlags |= TrustLineFlags::authorizedToMaintainLiabilities()->toNativeInt();
        } elseif ($authorizedToMaintainLiabilities === false) {
            $clearFlags |= TrustLineFlags::authorizedToMaintainLiabilities()->toNativeInt();
        }

        // Set the 'clawback enabled' flag.
        if ($clawbackEnabled === true) {
            $setFlags |= TrustLineFlags::trustlineClawbackEnabled()->toNativeInt();
        } elseif ($clawbackEnabled === false) {
            $clearFlags |= TrustLineFlags::trustlineClawbackEnabled()->toNativeInt();
        }

        // Create the operation
        $setTrustlineFlagsOp = (new static())
            ->withTrustor($trustor)
            ->withAsset($asset)
            ->withSetFlags($setFlags)
            ->withClearFlags($clearFlags);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::SET_TRUSTLINE_FLAGS, $setTrustlineFlagsOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->trustor) && $this->trustor instanceof AccountId)
            && (isset($this->asset) && $this->asset instanceof Asset);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_LOW;
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
        if (!isset($this->trustor)) {
            throw new InvalidArgumentException('The set trust line operation is missing a trustor');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The set trust line operation is missing an asset');
        }

        if (!isset($this->clearFlags)) {
            $this->clearFlags = UInt32::of(0);
        }

        if (!isset($this->setFlags)) {
            $this->setFlags = UInt32::of(0);
        }

        $xdr->write($this->trustor)
            ->write($this->asset)
            ->write($this->clearFlags)
            ->write($this->setFlags);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $setTrustLineFlagsOp = new static();
        $setTrustLineFlagsOp->trustor = $xdr->read(AccountId::class);
        $setTrustLineFlagsOp->asset = $xdr->read(Asset::class);
        $setTrustLineFlagsOp->clearFlags = $xdr->read(UInt32::class);
        $setTrustLineFlagsOp->setFlags = $xdr->read(UInt32::class);

        return $setTrustLineFlagsOp;
    }

    /**
     * Get the trustor.
     *
     * @return AccountId
     */
    public function getTrustor(): AccountId
    {
        return $this->trustor;
    }

    /**
     * Accept a trustor.
     *
     * @param AccountId|Addressable|string $trustor
     * @return static
     */
    public function withTrustor(AccountId|Addressable|string $trustor): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->trustor = AccountId::fromAddressable($trustor);

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
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->asset = is_string($asset)
            ? Asset::fromNativeString($asset)
            : Copy::deep($asset);

        return $clone;
    }

    /**
     * Get the flags to be cleared.
     *
     * @return UInt32
     */
    public function getClearFlags(): UInt32
    {
        return $this->clearFlags;
    }

    /**
     * Accept a set of flags to be cleared.
     *
     * @param UInt32|int $clearFlags
     * @return static
     */
    public function withClearFlags(UInt32|int $clearFlags): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->clearFlags = UInt32::of($clearFlags);

        return $clone;
    }

    /**
     * Get the flags to be set.
     *
     * @return UInt32
     */
    public function getSetFlags(): UInt32
    {
        return $this->setFlags;
    }

    /**
     * Accept flags to be set.
     *
     * @param UInt32|int $setFlags
     * @return static
     */
    public function withSetFlags(UInt32|int $setFlags): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->setFlags = UInt32::of($setFlags);

        return $clone;
    }
}
