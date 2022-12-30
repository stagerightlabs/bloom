<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<SponsorshipDescriptor>
 */
class SponsorshipDescriptorList extends Arr implements XdrArray
{
    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return SponsorshipDescriptor::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return Bloom::MAX_SIGNERS;
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
