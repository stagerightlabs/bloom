<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolWithdrawOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PoolId $liquidityPoolId;
    protected Int64 $amount; // amount of pool shares to withdraw
    protected Int64 $minAmountA; // Minimum amount of first asset to withdraw
    protected Int64 $minAmountB; // Minimum amount of second asset to withdraw

    /**
     * Create a new liquidity-pool-withdraw operation.
     *
     * @param PoolId|string $poolId
     * @param Int64|ScaledAmount|int|string $amount
     * @param Int64|ScaledAmount|int|string $minAmountA
     * @param Int64|ScaledAmount|int|string $minAmountB
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        PoolId|string $poolId,
        Int64|ScaledAmount|int|string $amount,
        Int64|ScaledAmount|int|string $minAmountA,
        Int64|ScaledAmount|int|string $minAmountB,
        Addressable|string $source = null,
    ): Operation {
        $liquidityPoolWithdrawOp = (new static())
            ->withLiquidityPoolId($poolId)
            ->withAmount($amount)
            ->withMinAmountA($minAmountA)
            ->withMinAmountB($minAmountB);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::LIQUIDITY_POOL_WITHDRAW, $liquidityPoolWithdrawOp))
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
            && (isset($this->amount) && $this->amount instanceof Int64)
            && (isset($this->minAmountA) && $this->minAmountA instanceof Int64)
            && (isset($this->minAmountB) && $this->minAmountB instanceof Int64);
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
            throw new InvalidArgumentException('The liquidity pool withdraw operation is missing a liquidity pool Id');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The liquidity pool withdraw operation is missing an amount of pool shares');
        }

        if (!isset($this->minAmountA)) {
            throw new InvalidArgumentException('The liquidity pool withdraw operation is missing a minimum amount for the first asset');
        }

        if (!isset($this->minAmountB)) {
            throw new InvalidArgumentException('The liquidity pool withdraw operation is missing a minimum amount for the second asset');
        }

        $xdr->write(($this->liquidityPoolId))
            ->write($this->amount)
            ->write($this->minAmountA)
            ->write($this->minAmountB);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liquidityPoolWithdrawOp = new static();
        $liquidityPoolWithdrawOp->liquidityPoolId = $xdr->read(PoolId::class);
        $liquidityPoolWithdrawOp->amount = $xdr->read(Int64::class);
        $liquidityPoolWithdrawOp->minAmountA = $xdr->read(Int64::class);
        $liquidityPoolWithdrawOp->minAmountB = $xdr->read(Int64::class);

        return $liquidityPoolWithdrawOp;
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
     * Get the amount of pool shares.
     *
     * @return Int64
     */
    public function getAmount(): Int64
    {
        return $this->amount;
    }

    /**
     * Accept an amount of pool shares.
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

    /**
     * Get the minimum amount of the first asset.
     *
     * @return Int64
     */
    public function getMinAmountA(): Int64
    {
        return $this->minAmountA;
    }

    /**
     * Accept a minimum amount for the first asset.
     *
     * @param Int64|ScaledAmount|int|string $minAmountA
     * @return static
     */
    public function withMinAmountA(Int64|ScaledAmount|int|string $minAmountA): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->minAmountA = Int64::normalize($minAmountA);

        return $clone;
    }

    /**
     * Get the minimum amount for the second asset.
     *
     * @return Int64
     */
    public function getMinAmountB(): Int64
    {
        return $this->minAmountB;
    }

    /**
     * Accept a minimum amount for the second asset.
     *
     * @param Int64|ScaledAmount|int|string $minAmountB
     * @return static
     */
    public function withMinAmountB(Int64|ScaledAmount|int|string $minAmountB): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->minAmountB = Int64::normalize($minAmountB);

        return $clone;
    }
}
