<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LedgerHeaderFlags extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const DISABLE_LIQUIDITY_POOL_TRADING = 1;
    public const DISABLE_LIQUIDITY_POOL_TRADING_FLAG = 'disableLiquidityPoolTradingFlag';
    public const DISABLE_LIQUIDITY_POOL_DEPOSIT = 2;
    public const DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG = 'disableLiquidityPoolDepositFlag';
    public const DISABLE_LIQUIDITY_POOL_WITHDRAWAL = 4;
    public const DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG = 'disableLiquidityPoolWithdrawalFlag';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            self::DISABLE_LIQUIDITY_POOL_TRADING    => self::DISABLE_LIQUIDITY_POOL_TRADING_FLAG,
            self::DISABLE_LIQUIDITY_POOL_DEPOSIT    => self::DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG,
            self::DISABLE_LIQUIDITY_POOL_WITHDRAWAL => self::DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG,
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
     * Retrieve the integer value of the selected flag.
     *
     * @return integer
     */
    public function toNativeInt(): int
    {
        return $this->getIndex();
    }

    /**
     * Create a new instance pre-selected as DISABLE_LIQUIDITY_POOL_TRADING_FLAG.
     *
     * @return static
     */
    public static function disableLiquidityPoolTradingFlag(): static
    {
        return (new static())->withValue(self::DISABLE_LIQUIDITY_POOL_TRADING_FLAG);
    }

    /**
     * Create a new instance pre-selected as DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG.
     *
     * @return static
     */
    public static function disableLiquidityPoolDepositFlag(): static
    {
        return (new static())->withValue(self::DISABLE_LIQUIDITY_POOL_DEPOSIT_FLAG);
    }

    /**
     * Create a new instance pre-selected as DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG.
     *
     * @return static
     */
    public static function disableLiquidityPoolWithdrawalFlag(): static
    {
        return (new static())->withValue(self::DISABLE_LIQUIDITY_POOL_WITHDRAWAL_FLAG);
    }
}
