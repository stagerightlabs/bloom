<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class OfferEntryFlags extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const PASSIVE = 1;
    public const PASSIVE_FLAG = 'passiveFlag'; // an offer with this flag will not act on and take a reverse offer of equal price

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            self::PASSIVE => self::PASSIVE_FLAG,
        ];
    }

    /**
     * Return the selected memo type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Retrieve the integer value of the selected flag.
     *
     * @return integer
     */
    public function toNativeInt(): int
    {
        return $this->getIndex();
    }

    /**
     * Create a new instance pre-selected as PASSIVE_FLAG.
     *
     * @return static
     */
    public static function passive(): static
    {
        return (new static())->withValue(self::PASSIVE_FLAG);
    }
}
