<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\LiquidityPool;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolConstantProductParameters implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Asset $assetA; // assetA < assetB
    protected Asset $assetB;
    protected Int32 $fee; // Fee is in basis points, so the actual rate is (fee/100)%

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->assetA)) {
            throw new InvalidArgumentException('The liquidity pool constant product parameters are missing an \'A\' asset');
        }

        if (!isset($this->assetB)) {
            throw new InvalidArgumentException('The liquidity pool constant product parameters are missing an \'B\' asset');
        }

        if (!isset($this->fee)) {
            throw new InvalidArgumentException('The liquidity pool constant product parameters are missing a fee');
        }

        $xdr->write($this->assetA)
            ->write($this->assetB)
            ->write($this->fee);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liquidityPoolConstantProductParameters = new static();
        $liquidityPoolConstantProductParameters->assetA = $xdr->read(Asset::class);
        $liquidityPoolConstantProductParameters->assetB = $xdr->read(Asset::class);
        $liquidityPoolConstantProductParameters->fee = $xdr->read(Int32::class);

        return $liquidityPoolConstantProductParameters;
    }

    /**
     * Get the 'A' asset.
     *
     * @return Asset
     */
    public function getAssetA(): Asset
    {
        return $this->assetA;
    }

    /**
     * Accept an 'A' asset.
     *
     * @param Asset|string $assetA
     * @return static
     */
    public function withAssetA(Asset|string $assetA): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->assetA = is_string($assetA)
            ? Asset::fromNativeString($assetA)
            : Copy::deep($assetA);

        return $clone;
    }

    /**
     * Get the 'B' asset.
     *
     * @return Asset
     */
    public function getAssetB(): Asset
    {
        return $this->assetB;
    }

    /**
     * Accept a 'B' asset.
     *
     * @param Asset|string $assetB
     * @return static
     */
    public function withAssetB(Asset|string $assetB): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->assetB = is_string($assetB)
            ? Asset::fromNativeString($assetB)
            : Copy::deep($assetB);

        return $clone;
    }

    /**
     * Get the fee.
     *
     * @return Int32
     */
    public function getFee(): Int32
    {
        return $this->fee;
    }

    /**
     * Accept a fee.
     *
     * @param Int32|int|null $fee
     * @return static
     */
    public function withFee(Int32|int $fee = null): static
    {
        if (is_null($fee)) {
            $fee = Bloom::LIQUIDITY_POOL_FEE_V18;
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->fee = Int32::of($fee);

        return $clone;
    }
}
