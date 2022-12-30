<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolConstantProductParameters;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolEntryConstantProduct implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected LiquidityPoolConstantProductParameters $params;
    protected Int64 $reservesA; // Amount of asset A in the pool
    protected Int64 $reservesB; // Amount of asset B in the pool
    protected Int64 $totalPoolShares; // total number of shares issued
    protected Int64 $poolSharesTrustLineCount; // number of trust lines for the associated pool shares

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->params)) {
            throw new InvalidArgumentException('The liquidity pool entry constant product is missing parameters');
        }

        if (!isset($this->reservesA)) {
            throw new InvalidArgumentException('The liquidity pool entry constant product is missing \'A\' reserves');
        }

        if (!isset($this->reservesB)) {
            throw new InvalidArgumentException('The liquidity pool entry constant product is missing \'B\' reserves');
        }

        if (!isset($this->totalPoolShares)) {
            throw new InvalidArgumentException('The liquidity pool entry constant product is missing total pool shares');
        }

        if (!isset($this->poolSharesTrustLineCount)) {
            throw new InvalidArgumentException('The liquidity pool entry constant product is missing the pool shares trust line count');
        }

        $xdr->write($this->params)
            ->write($this->reservesA)
            ->write($this->reservesB)
            ->write($this->totalPoolShares)
            ->write($this->poolSharesTrustLineCount);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liquidityPoolEntryConstantProduct = new static();
        $liquidityPoolEntryConstantProduct->params = $xdr->read(LiquidityPoolConstantProductParameters::class);
        $liquidityPoolEntryConstantProduct->reservesA = $xdr->read(Int64::class);
        $liquidityPoolEntryConstantProduct->reservesB = $xdr->read(Int64::class);
        $liquidityPoolEntryConstantProduct->totalPoolShares = $xdr->read(Int64::class);
        $liquidityPoolEntryConstantProduct->poolSharesTrustLineCount = $xdr->read(Int64::class);

        return $liquidityPoolEntryConstantProduct;
    }

    /**
     * Get the params.
     *
     * @return LiquidityPoolConstantProductParameters
     */
    public function getParams(): LiquidityPoolConstantProductParameters
    {
        return $this->params;
    }

    /**
     * Accept parameters.
     *
     * @param LiquidityPoolConstantProductParameters $params
     * @return static
     */
    public function withParams(LiquidityPoolConstantProductParameters $params): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->params = Copy::deep($params);

        return $clone;
    }

    /**
     * Get the 'A' reserves.
     *
     * @return Int64
     */
    public function getReservesA(): Int64
    {
        return $this->reservesA;
    }

    /**
     * Accept an 'A' reserves amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $reservesA
     * @return static
     */
    public function withReservesA(Int64|ScaledAmount|int|string $reservesA): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->reservesA = Int64::normalize($reservesA);

        return $clone;
    }

    /**
     * Get the 'B' reserves.
     *
     * @return Int64
     */
    public function getReservesB(): Int64
    {
        return $this->reservesB;
    }

    /**
     * Accept a 'B' reserves amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $reservesB
     * @return static
     */
    public function withReservesB(Int64|ScaledAmount|int|string $reservesB): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->reservesB = Int64::normalize($reservesB);

        return $clone;
    }

    /**
     * Get the total pool shares.
     *
     * @return Int64
     */
    public function getTotalPoolShares(): Int64
    {
        return $this->totalPoolShares;
    }

    /**
     * Accept a total pool shares count.
     *
     * @param Int64 $totalPoolShares
     * @return static
     */
    public function withTotalPoolShares(Int64 $totalPoolShares): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->totalPoolShares = Copy::deep($totalPoolShares);

        return $clone;
    }

    /**
     * Get the pool shares trust line count.
     *
     * @return Int64
     */
    public function getPoolSharesTrustlineCount(): Int64
    {
        return $this->poolSharesTrustLineCount;
    }

    /**
     * Accept a pool shares trust line count.
     *
     * @param Int64 $poolSharesTrustLineCount
     * @return static
     */
    public function withPoolSharesTrustLineCount(Int64 $poolSharesTrustLineCount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->poolSharesTrustLineCount = Copy::deep($poolSharesTrustLineCount);

        return $clone;
    }
}
