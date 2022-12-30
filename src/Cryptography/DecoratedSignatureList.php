<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<DecoratedSignature>
 */
class DecoratedSignatureList extends Arr implements XdrArray
{
    public const MAX_SIGNATURE_COUNT = 20;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return DecoratedSignature::class;
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
     * Instantiate an empty signature list.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }
}
