<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class BucketEntryType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const META_ENTRY = 'metaEntry';
    public const LIVE_ENTRY = 'liveEntry';
    public const DEAD_ENTRY = 'deadEntry';
    public const INIT_ENTRY = 'initEntry';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            -1 => self::META_ENTRY,
            0  => self::LIVE_ENTRY,
            1  => self::DEAD_ENTRY,
            2  => self::INIT_ENTRY,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as META_ENTRY.
     *
     * @return static
     */
    public static function meta(): static
    {
        return (new static())->withValue(self::META_ENTRY);
    }

    /**
     * Create a new instance pre-selected as LIVE_ENTRY.
     *
     * @return static
     */
    public static function live(): static
    {
        return (new static())->withValue(self::LIVE_ENTRY);
    }

    /**
     * Create a new instance pre-selected as DEAD_ENTRY.
     *
     * @return static
     */
    public static function dead(): static
    {
        return (new static())->withValue(self::DEAD_ENTRY);
    }

    /**
     * Create a new instance pre-selected as INIT_ENTRY.
     *
     * @return static
     */
    public static function init(): static
    {
        return (new static())->withValue(self::INIT_ENTRY);
    }
}
