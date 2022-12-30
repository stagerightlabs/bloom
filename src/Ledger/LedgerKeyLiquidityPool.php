<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerKeyLiquidityPool implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PoolId $liquidityPoolId;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->liquidityPoolId)) {
            throw new InvalidArgumentException('The ledger key liquidity pool is missing a liquidity pool Id');
        }

        $xdr->write($this->liquidityPoolId);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerKeyLiquidityPool = new static();
        $ledgerKeyLiquidityPool->liquidityPoolId = $xdr->read(PoolId::class);

        return $ledgerKeyLiquidityPool;
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
     * Accept a liquidity pool Id.
     *
     * @param PoolId|string $liquidityPoolId
     * @return static
     */
    public function withLiquidityPoolId(PoolId|string $liquidityPoolId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->liquidityPoolId = PoolId::wrap($liquidityPoolId);

        return $clone;
    }
}
