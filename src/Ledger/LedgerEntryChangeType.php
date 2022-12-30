<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class LedgerEntryChangeType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const LEDGER_ENTRY_CREATED = 'ledgerEntryCreated';
    public const LEDGER_ENTRY_UPDATED = 'ledgerEntryUpdated';
    public const LEDGER_ENTRY_REMOVED = 'ledgerEntryRemoved';
    public const LEDGER_ENTRY_STATE = 'ledgerEntryState';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::LEDGER_ENTRY_CREATED,
            1 => self::LEDGER_ENTRY_UPDATED,
            2 => self::LEDGER_ENTRY_REMOVED,
            3 => self::LEDGER_ENTRY_STATE,
        ];
    }

    /**
     * Return the selected ledger entry change type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as LEDGER_ENTRY_CREATED.
     *
     * @return static
     */
    public static function entryCreated(): static
    {
        return (new static())->withValue(self::LEDGER_ENTRY_CREATED);
    }

    /**
     * Create a new instance pre-selected as LEDGER_ENTRY_UPDATED.
     *
     * @return static
     */
    public static function entryUpdated(): static
    {
        return (new static())->withValue(self::LEDGER_ENTRY_UPDATED);
    }

    /**
     * Create a new instance pre-selected as LEDGER_ENTRY_REMOVED.
     *
     * @return static
     */
    public static function entryRemoved(): static
    {
        return (new static())->withValue(self::LEDGER_ENTRY_REMOVED);
    }

    /**
     * Create a new instance pre-selected as LEDGER_ENTRY_STATE.
     *
     * @return static
     */
    public static function entryState(): static
    {
        return (new static())->withValue(self::LEDGER_ENTRY_STATE);
    }
}
