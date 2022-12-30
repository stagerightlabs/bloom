<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LedgerEntryType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ACCOUNT = 'account';
    public const TRUSTLINE = 'trustline';
    public const OFFER = 'offer';
    public const DATA = 'data';
    public const CLAIMABLE_BALANCE = 'claimableBalance';
    public const LIQUIDITY_POOL = 'liquidityPool';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::ACCOUNT,
            1 => self::TRUSTLINE,
            2 => self::OFFER,
            3 => self::DATA,
            4 => self::CLAIMABLE_BALANCE,
            5 => self::LIQUIDITY_POOL,
        ];
    }

    /**
     * Return the selected leger entry type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as ACCOUNT.
     *
     * @return static
     */
    public static function account(): static
    {
        return (new static())->withValue(self::ACCOUNT);
    }

    /**
     * Create a new instance pre-selected as TRUSTLINE.
     *
     * @return static
     */
    public static function trustline(): static
    {
        return (new static())->withValue(self::TRUSTLINE);
    }

    /**
     * Create a new instance pre-selected as OFFER.
     *
     * @return static
     */
    public static function offer(): static
    {
        return (new static())->withValue(self::OFFER);
    }

    /**
     * Create a new instance pre-selected as DATA.
     *
     * @return static
     */
    public static function data(): static
    {
        return (new static())->withValue(self::DATA);
    }

    /**
     * Create a new instance pre-selected as CLAIMABLE_BALANCE.
     *
     * @return static
     */
    public static function claimableBalance(): static
    {
        return (new static())->withValue(self::CLAIMABLE_BALANCE);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL.
     *
     * @return static
     */
    public static function liquidityPool(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL);
    }
}
