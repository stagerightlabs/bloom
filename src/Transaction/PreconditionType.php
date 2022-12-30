<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class PreconditionType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const PRECONDITION_NONE = 'precondNone';
    public const PRECONDITION_TIME = 'precondTime';
    public const PRECONDITION_V2 = 'precondV2';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::PRECONDITION_NONE,
            1 => self::PRECONDITION_TIME,
            2 => self::PRECONDITION_V2,
        ];
    }

    /**
     * Return the selected operation type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance that is preselected as PRECONDITION_NONE.
     *
     * @return static
     */
    public static function none(): static
    {
        return (new static())->withValue(self::PRECONDITION_NONE);
    }

    /**
     * Create a new instance that is preselected as PRECONDITION_TIME.
     *
     * @return static
     */
    public static function time()
    {
        return (new static())->withValue(self::PRECONDITION_TIME);
    }

    /**
     * Create a new instance that is preselected as PRECONDITION_V2.
     *
     * @return static
     */
    public static function v2(): static
    {
        return (new static())->withValue(self::PRECONDITION_V2);
    }
}
