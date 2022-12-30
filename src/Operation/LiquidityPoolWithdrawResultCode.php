<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LiquidityPoolWithdrawResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const LIQUIDITY_POOL_WITHDRAW_SUCCESS = 'liquidityPoolWithdrawSuccess';
    public const LIQUIDITY_POOL_WITHDRAW_MALFORMED = 'liquidityPoolWithdrawMalformed'; // bad input
    public const LIQUIDITY_POOL_WITHDRAW_NO_TRUST = 'liquidityPoolWithdrawNoTrust'; // no trust line for one of the assets
    public const LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED = 'liquidityPoolWithdrawUnderfunded'; // not enough balance of the pool share
    public const LIQUIDITY_POOL_WITHDRAW_LINE_FULL = 'liquidityPoolWithdrawLineFull'; // would go above limit for one of the assets
    public const LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM = 'liquidityPoolWithdrawUnderMinimum'; // didn't withdraw enough

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::LIQUIDITY_POOL_WITHDRAW_SUCCESS,
            -1 => self::LIQUIDITY_POOL_WITHDRAW_MALFORMED,
            -2 => self::LIQUIDITY_POOL_WITHDRAW_NO_TRUST,
            -3 => self::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED,
            -4 => self::LIQUIDITY_POOL_WITHDRAW_LINE_FULL,
            -5 => self::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM.
     *
     * @return static
     */
    public static function underMinimum(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM);
    }
}
