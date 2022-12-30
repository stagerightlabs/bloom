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

final class LiquidityPoolEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PoolId $liquidityPoolId;
    protected LiquidityPoolEntryBody $body;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->liquidityPoolId)) {
            throw new InvalidArgumentException('The liquidity pool entry is missing a pool Id');
        }

        if (!isset($this->body)) {
            throw new InvalidArgumentException('The liquidity pool entry is missing a body');
        }

        $xdr->write($this->liquidityPoolId)->write($this->body);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liquidityPoolEntry = new static();
        $liquidityPoolEntry->liquidityPoolId = $xdr->read(PoolId::class);
        $liquidityPoolEntry->body = $xdr->read(LiquidityPoolEntryBody::class);

        return $liquidityPoolEntry;
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
     * Accept a pool Id.
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
     * Get the body.
     *
     * @return LiquidityPoolEntryBody
     */
    public function getBody(): LiquidityPoolEntryBody
    {
        return $this->body;
    }

    /**
     * Accept a body.
     *
     * @param LiquidityPoolEntryBody $body
     * @return static
     */
    public function withBody(LiquidityPoolEntryBody $body): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->body = Copy::deep($body);

        return $clone;
    }
}
