<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<SignerKey>
 */
class ExtraSigners extends Arr implements XdrArray
{
    public const MAX_SIGNATURE_COUNT = 2;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return SignerKey::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return self::MAX_SIGNATURE_COUNT;
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
