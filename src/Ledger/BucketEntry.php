<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class BucketEntry extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return BucketEntryType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            BucketEntryType::LIVE_ENTRY => LedgerEntry::class,
            BucketEntryType::INIT_ENTRY => LedgerEntry::class,
            BucketEntryType::DEAD_ENTRY => LedgerKey::class,
            BucketEntryType::META_ENTRY => BucketMetadata::class,
        ];
    }

    /**
     * Create a new 'live' instance by wrapping a ledger entry.
     *
     * @param LedgerEntry $ledgerEntry
     * @return static
     */
    public static function wrapLiveLedgerEntry(LedgerEntry $ledgerEntry): static
    {
        $bucketEntry = new static();
        $bucketEntry->discriminator = BucketEntryType::live();
        $bucketEntry->value = $ledgerEntry;

        return $bucketEntry;
    }

    /**
     * Create a new 'init' instance by wrapping a ledger entry.
     *
     * @param LedgerEntry $ledgerEntry
     * @return static
     */
    public static function wrapInitLedgerEntry(LedgerEntry $ledgerEntry): static
    {
        $bucketEntry = new static();
        $bucketEntry->discriminator = BucketEntryType::init();
        $bucketEntry->value = $ledgerEntry;

        return $bucketEntry;
    }

    /**
     * Create a new 'dead' instance by wrapping a ledger key.
     *
     * @param LedgerKey $ledgerKey
     * @return static
     */
    public static function wrapDeadLedgerKey(LedgerKey $ledgerKey): static
    {
        $bucketEntry = new static();
        $bucketEntry->discriminator = BucketEntryType::dead();
        $bucketEntry->value = $ledgerKey;

        return $bucketEntry;
    }

    /**
     * Create a new 'meta' instance by wrapping a bucket metadata object.
     *
     * @param BucketMetadata $bucketMetadata
     * @return static
     */
    public static function wrapBucketMetadata(BucketMetadata $bucketMetadata): static
    {
        $bucketEntry = new static();
        $bucketEntry->discriminator = BucketEntryType::meta();
        $bucketEntry->value = $bucketMetadata;

        return $bucketEntry;
    }

    /**
     * Return the underlying value.
     *
     * @return LedgerEntry|LedgerKey|BucketMetadata|null
     */
    public function unwrap(): LedgerEntry|LedgerKey|BucketMetadata|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof BucketEntryType) {
            return  $this->discriminator->getType();
        }

        return null;
    }
}
