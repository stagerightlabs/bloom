<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LiquidityPoolDepositResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const LIQUIDITY_POOL_DEPOSIT_SUCCESS = 'liquidityPoolDepositSuccess';
    public const LIQUIDITY_POOL_DEPOSIT_MALFORMED = 'liquidityPoolDepositMalformed'; // bad input
    public const LIQUIDITY_POOL_DEPOSIT_NO_TRUST = 'liquidityPoolDepositNoTrust'; // no trust line for one of the assets
    public const LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED = 'liquidityPoolDepositNotAuthorized'; // not authorized for one of the assets
    public const LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED = 'liquidityPoolDepositUnderfunded'; // not enough balance for one of the assets
    public const LIQUIDITY_POOL_DEPOSIT_LINE_FULL = 'liquidityPoolDepositLineFull'; // pool share trust line doesn't have sufficient limit
    public const LIQUIDITY_POOL_DEPOSIT_BAD_PRICE = 'liquidityPoolDepositBadPrice'; // deposit price outside bounds
    public const LIQUIDITY_POOL_DEPOSIT_POOL_FULL = 'liquidityPoolDepositPoolFull'; // pool reserves are full

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::LIQUIDITY_POOL_DEPOSIT_SUCCESS,
            -1 => self::LIQUIDITY_POOL_DEPOSIT_MALFORMED,
            -2 => self::LIQUIDITY_POOL_DEPOSIT_NO_TRUST,
            -3 => self::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED,
            -4 => self::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED,
            -5 => self::LIQUIDITY_POOL_DEPOSIT_LINE_FULL,
            -6 => self::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE,
            -7 => self::LIQUIDITY_POOL_DEPOSIT_POOL_FULL,
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
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_BAD_PRICE.
     *
     * @return static
     */
    public static function badPrice(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT_POOL_FULL.
     *
     * @return static
     */
    public static function poolFull(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT_POOL_FULL);
    }
}
