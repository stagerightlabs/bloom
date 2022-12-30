<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<LedgerEntryChange>
 */
class LedgerEntryChanges extends Arr implements XdrArray
{
    /**
     * Properties
     */
    public const MAX_LENGTH = 2147483647;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return LedgerEntryChange::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return self::MAX_LENGTH;
    }

    /**
     * Instantiate an empty array.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }
}
