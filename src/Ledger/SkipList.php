<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<Hash>
 */
class SkipList extends Arr implements XdrArray
{
    /**
     * Properties
     */
    public const MAX_LENGTH = 4;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return Hash::class;
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
     * Specify a length to encode as a fixed length array. Otherwise return
     * null to encode as a variable length array.
     *
     * @return int|null
     */
    public static function getXdrLength(): ?int
    {
        return self::MAX_LENGTH;
    }
}
