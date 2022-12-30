<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class MemoType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const MEMO_NONE = 'memoNone';
    public const MEMO_TEXT = 'memoText';
    public const MEMO_ID = 'memoId';
    public const MEMO_HASH = 'memoHash';
    public const MEMO_RETURN = 'memoReturn';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::MEMO_NONE,
            1 => self::MEMO_TEXT,
            2 => self::MEMO_ID,
            3 => self::MEMO_HASH,
            4 => self::MEMO_RETURN,
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
     * Specify a default selection as a fallback.
     *
     * @return int|null
     */
    protected static function getDefaultSelection(): ?int
    {
        return 0;
    }

    /**
     * Create a new instance pre-selected as MEMO_NONE.
     *
     * @return static
     */
    public static function none(): static
    {
        return (new static())->withValue(self::MEMO_NONE);
    }

    /**
     * Create a new instance pre-selected as MEMO_TEXT.
     *
     * @return static
     */
    public static function text(): static
    {
        return (new static())->withValue(self::MEMO_TEXT);
    }

    /**
     * Create a new instance pre-selected as MEMO_ID.
     *
     * @return static
     */
    public static function id(): static
    {
        return (new static())->withValue(self::MEMO_ID);
    }

    /**
     * Create a new instance pre-selected as MEMO_HASH.
     *
     * @return static
     */
    public static function hash(): static
    {
        return (new static())->withValue(self::MEMO_HASH);
    }

    /**
     * Create a new instance pre-selected as MEMO_RETURN.
     *
     * @return static
     */
    public static function return(): static
    {
        return (new static())->withValue(self::MEMO_RETURN);
    }
}
