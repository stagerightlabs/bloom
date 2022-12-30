<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Offer;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClaimLiquidityAtom implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PoolId $liquidityPoolId;
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
        if (!isset($this->liquidityPoolId)) {
            throw new InvalidArgumentException('The ClaimLiquidityAtom is missing a liquidity pool Id');
        }

        if (!isset($this->assetSold)) {
            throw new InvalidArgumentException('The ClaimLiquidityAtom is missing the asset sold');
        }

        if (!isset($this->amountSold)) {
            throw new InvalidArgumentException('The ClaimLiquidityAtom is missing the amount sold');
        }

        if (!isset($this->assetBought)) {
            throw new InvalidArgumentException('The ClaimLiquidityAtom is missing the asset bought');
        }

        if (!isset($this->amountBought)) {
            throw new InvalidArgumentException('The ClaimLiquidityAtom is missing the amount');
        }

        $xdr->write($this->liquidityPoolId)
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
        $claimLiquidityAtom = new static();
        $claimLiquidityAtom->liquidityPoolId = $xdr->read(PoolId::class);
        $claimLiquidityAtom->assetSold = $xdr->read(Asset::class);
        $claimLiquidityAtom->amountSold = $xdr->read(Int64::class);
        $claimLiquidityAtom->assetBought = $xdr->read(Asset::class);
        $claimLiquidityAtom->amountBought = $xdr->read(Int64::class);

        return $claimLiquidityAtom;
    }

    /**
     * Get the liquidity pool Id.
     *
     * @return PoolId
     */
    public function getLiquidityPoolId(): PoolId
    {
        return $this->liquidityPoolId;
    }

    /**
     * Accept a liquidity pool id..
     *
     * @param PoolId $liquidityPoolId
     * @return static
     */
    public function withLiquidityPoolId(PoolId $liquidityPoolId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->liquidityPoolId = Copy::deep($liquidityPoolId);

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
     * Get the asset bought..
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
