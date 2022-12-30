<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class LedgerEntryChange extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LedgerEntryChangeType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LedgerEntryChangeType::LEDGER_ENTRY_CREATED => LedgerEntry::class,
            LedgerEntryChangeType::LEDGER_ENTRY_UPDATED => LedgerEntry::class,
            LedgerEntryChangeType::LEDGER_ENTRY_REMOVED => LedgerKey::class,
            LedgerEntryChangeType::LEDGER_ENTRY_STATE   => LedgerEntry::class,
        ];
    }

    /**
     * Create a new 'entry created' instance by wrapping a ledger entry.
     *
     * @param LedgerEntry $ledgerEntry
     * @return static
     */
    public static function wrapCreatedLedgerEntry(LedgerEntry $ledgerEntry): static
    {
        $ledgerEntryChange = new static();
        $ledgerEntryChange->discriminator = LedgerEntryChangeType::entryCreated();
        $ledgerEntryChange->value = $ledgerEntry;

        return $ledgerEntryChange;
    }

    /**
     * Create a new 'entry updated' instance by wrapping a ledger entry.
     *
     * @param LedgerEntry $ledgerEntry
     * @return static
     */
    public static function wrapUpdatedLedgerEntry(LedgerEntry $ledgerEntry): static
    {
        $ledgerEntryChange = new static();
        $ledgerEntryChange->discriminator = LedgerEntryChangeType::entryUpdated();
        $ledgerEntryChange->value = $ledgerEntry;

        return $ledgerEntryChange;
    }

    /**
     * Create a new 'entry removed' instance by wrapping a ledger key.
     *
     * @param LedgerKey $ledgerKey
     * @return static
     */
    public static function wrapRemovedLedgerKey(LedgerKey $ledgerKey): static
    {
        $ledgerEntryChange = new static();
        $ledgerEntryChange->discriminator = LedgerEntryChangeType::entryRemoved();
        $ledgerEntryChange->value = $ledgerKey;

        return $ledgerEntryChange;
    }

    /**
     * Create a new 'state' instance by wrapping a ledger entry.
     *
     * @param LedgerEntry $ledgerEntry
     * @return static
     */
    public static function wrapStateLedgerEntry(LedgerEntry $ledgerEntry): static
    {
        $ledgerEntryChange = new static();
        $ledgerEntryChange->discriminator = LedgerEntryChangeType::entryState();
        $ledgerEntryChange->value = $ledgerEntry;

        return $ledgerEntryChange;
    }

    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LedgerEntryChangeType) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Return the underlying value.
     *
     * @return LedgerEntry|LedgerKey|null
     */
    public function unwrap(): LedgerEntry|LedgerKey|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
