<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\ClaimableBalance\Claimant;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class CreateClaimableBalanceOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $asset;
    protected Int64 $amount;
    protected ClaimantList $claimants;

    /**
     * Create a new create-claimable-balance operation.
     *
     * @param Asset|string $asset
     * @param Int64|ScaledAmount|integer|string $amount
     * @param ClaimantList|Claimant|array<Claimant|string> $claimants
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        ClaimantList|Claimant|array $claimants,
        Addressable|string $source = null,
    ): Operation {
        $createClaimableBalanceOp = (new static())
            ->withAsset($asset)
            ->withAmount($amount)
            ->withClaimants($claimants);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CREATE_CLAIMABLE_BALANCE, $createClaimableBalanceOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->asset) && $this->asset instanceof Asset)
            && (isset($this->amount) && $this->amount instanceof Int64)
            && (isset($this->claimants) && $this->claimants instanceof ClaimantList);
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
            throw new InvalidArgumentException('The create claimable balance operation is missing an asset');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The claimable balance operation is missing an amount');
        }

        if (!isset($this->claimants)) {
            $this->claimants = ClaimantList::empty();
        }

        $xdr->write($this->asset)
            ->write($this->amount)
            ->write($this->claimants);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $createClaimableBalanceOp = new static();
        $createClaimableBalanceOp->asset = $xdr->read(Asset::class);
        $createClaimableBalanceOp->amount = $xdr->read(Int64::class);
        $createClaimableBalanceOp->claimants = $xdr->read(ClaimantList::class);

        return $createClaimableBalanceOp;
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
     * @param ClaimantList|Claimant|array<Claimant|string> $claimants
     * @return static
     */
    public function withClaimants(ClaimantList|Claimant|array $claimants): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->claimants = ClaimantList::normalize($claimants);

        return $clone;
    }
}
