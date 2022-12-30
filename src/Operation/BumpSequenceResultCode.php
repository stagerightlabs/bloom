<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class BumpSequenceResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const BUMP_SEQUENCE_SUCCESS = 'bumpSequenceSuccess';
    public const BUMP_SEQUENCE_BAD_SEQ = 'bumpSequenceBadSeq'; // 'bumpTo' is not within bounds

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::BUMP_SEQUENCE_SUCCESS,
            -1 => self::BUMP_SEQUENCE_BAD_SEQ,
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
     * Create a new instance pre-selected as BUMP_SEQUENCE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::BUMP_SEQUENCE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as BUMP_SEQUENCE_BAD_SEQ.
     *
     * @return static
     */
    public static function badSequenceNumber(): static
    {
        return (new static())->withValue(self::BUMP_SEQUENCE_BAD_SEQ);
    }
}
