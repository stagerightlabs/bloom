<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolDepositOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PoolId $liquidityPoolId;
    protected Int64 $maxAmountA; // Maximum amount of first asset to deposit
    protected Int64 $maxAmountB; // Maximum amount of second asset to deposit
    protected Price $minPrice; // Minimum depositA/depositB
    protected Price $maxPrice; // Maximum depositA/depositB

    /**
     * Create a new liquidity-pool-deposit operation.
     *
     * @param PoolId|string $poolId
     * @param Int64|ScaledAmount|int|string $maxAmountA
     * @param Int64|ScaledAmount|int|string $maxAmountB
     * @param Price|string $minPrice
     * @param Price|string $maxPrice
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        PoolId|string $poolId,
        Int64|ScaledAmount|int|string $maxAmountA,
        Int64|ScaledAmount|int|string $maxAmountB,
        Price|string $minPrice,
        Price|string $maxPrice,
        Addressable|string $source = null,
    ): Operation {
        $liquidityPoolDepositOp = (new static())
            ->withLiquidityPoolId($poolId)
            ->withMaxAmountA($maxAmountA)
            ->withMaxAmountB($maxAmountB)
            ->withMinPrice($minPrice)
            ->withMaxPrice($maxPrice);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::LIQUIDITY_POOL_DEPOSIT, $liquidityPoolDepositOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->liquidityPoolId) && $this->liquidityPoolId instanceof PoolId)
            && (isset($this->maxAmountA) && $this->maxAmountA instanceof Int64)
            && (isset($this->maxAmountB) && $this->maxAmountB instanceof Int64)
            && (isset($this->minPrice) && $this->minPrice instanceof Price)
            && (isset($this->maxPrice) && $this->maxPrice instanceof Price);
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
        if (!isset($this->liquidityPoolId)) {
            throw new InvalidArgumentException('The liquidity pool deposit operation is missing a liquidity pool id');
        }

        if (!isset($this->maxAmountA)) {
            throw new InvalidArgumentException('The liquidity pool deposit operation is missing a maximum amount for the first asset');
        }

        if (!isset($this->maxAmountB)) {
            throw new InvalidArgumentException('The liquidity pool deposit operation is missing a maximum amount for the second asset');
        }

        if (!isset($this->minPrice)) {
            throw new InvalidArgumentException('The liquidity pool deposit operation is missing a minimum price');
        }

        if (!isset($this->maxPrice)) {
            throw new InvalidArgumentException('The liquidity pool deposit operation is missing a maximum price');
        }

        $xdr->write($this->liquidityPoolId)
            ->write($this->maxAmountA)
            ->write($this->maxAmountB)
            ->write($this->minPrice)
            ->write($this->maxPrice);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liquidityPoolDepositOp = new static();
        $liquidityPoolDepositOp->liquidityPoolId = $xdr->read(PoolId::class);
        $liquidityPoolDepositOp->maxAmountA = $xdr->read(Int64::class);
        $liquidityPoolDepositOp->maxAmountB = $xdr->read(Int64::class);
        $liquidityPoolDepositOp->minPrice = $xdr->read(Price::class);
        $liquidityPoolDepositOp->maxPrice = $xdr->read(Price::class);

        return $liquidityPoolDepositOp;
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
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->liquidityPoolId = PoolId::wrap($liquidityPoolId);

        return $clone;
    }

    /**
     * Get the maximum amount of the first asset.
     *
     * @return Int64
     */
    public function getMaxAmountA(): Int64
    {
        return $this->maxAmountA;
    }

    /**
     * Accept a maximum amount for the first asset.
     *
     * @param Int64|ScaledAmount|int|string $maxAmountA
     * @return static
     */
    public function withMaxAmountA(Int64|ScaledAmount|int|string $maxAmountA): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->maxAmountA = Int64::normalize($maxAmountA);

        return $clone;
    }

    /**
     * Get the maximum amount of the second asset.
     *
     * @return Int64
     */
    public function getMaxAmountB(): Int64
    {
        return $this->maxAmountB;
    }

    /**
     * Accept a maximum amount for the second asset.
     *
     * @param Int64|ScaledAmount|int|string $maxAmountB
     * @return static
     */
    public function withMaxAmountB(Int64|ScaledAmount|int|string $maxAmountB): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->maxAmountB = Int64::normalize($maxAmountB);

        return $clone;
    }

    /**
     * Get the minimum price.
     *
     * @return Price
     */
    public function getMinPrice(): Price
    {
        return $this->minPrice;
    }

    /**
     * Accept a minimum price.
     *
     * @param Price|string $minPrice
     * @return static
     */
    public function withMinPrice(Price|string $minPrice): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->minPrice = is_string($minPrice)
            ? Price::fromNativeString($minPrice)
            : Copy::deep($minPrice);

        return $clone;
    }

    /**
     * Get the maximum price.
     *
     * @return Price
     */
    public function getMaxPrice(): Price
    {
        return $this->maxPrice;
    }

    /**
     * Accept a maximum price.
     *
     * @param Price|string $maxPrice
     * @return static
     */
    public function withMaxPrice(Price|string $maxPrice): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->maxPrice = is_string($maxPrice)
            ? Price::fromNativeString($maxPrice)
            : Copy::deep($maxPrice);

        return $clone;
    }
}
