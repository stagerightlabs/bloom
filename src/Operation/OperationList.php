<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<Operation>
 */
class OperationList extends Arr implements XdrArray
{
    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return Operation::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return Bloom::MAX_OPS_PER_TX;
    }

    /**
     * Instantiate an empty operations list.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }
}
