<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LedgerUpgradeType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const LEDGER_UPGRADE_VERSION = 'ledgerUpgradeVersion';
    public const LEDGER_UPGRADE_BASE_FEE = 'ledgerUpgradeBaseFee';
    public const LEDGER_UPGRADE_MAX_TX_SET_SIZE = 'ledgerUpgradeMaxTxSetSize';
    public const LEDGER_UPGRADE_BASE_RESERVE = 'ledgerUpgradeBaseReserve';
    public const LEDGER_UPGRADE_FLAGS = 'ledgerUpgradeFlags';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            1 => self::LEDGER_UPGRADE_VERSION,
            2 => self::LEDGER_UPGRADE_BASE_FEE,
            3 => self::LEDGER_UPGRADE_MAX_TX_SET_SIZE,
            4 => self::LEDGER_UPGRADE_BASE_RESERVE,
            5 => self::LEDGER_UPGRADE_FLAGS,
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
     * Create a new instance pre-selected as LEDGER_UPGRADE_VERSION.
     *
     * @return static
     */
    public static function version(): static
    {
        return (new static())->withValue(self::LEDGER_UPGRADE_VERSION);
    }

    /**
     * Create a new instance pre-selected as LEDGER_UPGRADE_BASE_FEE.
     *
     * @return static
     */
    public static function baseFee(): static
    {
        return (new static())->withValue(self::LEDGER_UPGRADE_BASE_FEE);
    }

    /**
     * Create a new instance pre-selected as LEDGER_UPGRADE_MAX_TS_SET_SIZE.
     *
     * @return static
     */
    public static function maxTxSetSize(): static
    {
        return (new static())->withValue(self::LEDGER_UPGRADE_MAX_TX_SET_SIZE);
    }

    /**
     * Create a new instance pre-selected as LEDGER_UPGRADE_BASE_RESERVE.
     *
     * @return static
     */
    public static function baseReserve(): static
    {
        return (new static())->withValue(self::LEDGER_UPGRADE_BASE_RESERVE);
    }

    /**
     * Create a new instance pre-selected as LEDGER_UPGRADE_FLAGS.
     *
     * @return static
     */
    public static function flags(): static
    {
        return (new static())->withValue(self::LEDGER_UPGRADE_FLAGS);
    }
}
