<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ScpStatementType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SCP_ST_PREPARE = 'scpStPrepare';
    public const SCP_ST_CONFIRM = 'scpStConfirm';
    public const SCP_ST_EXTERNALIZE = 'scpStExternalize';
    public const SCP_ST_NOMINATE = 'scpStNominate';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::SCP_ST_PREPARE,
            1 => self::SCP_ST_CONFIRM,
            2 => self::SCP_ST_EXTERNALIZE,
            3 => self::SCP_ST_NOMINATE,
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
     * Create a new instance pre-selected as SCP_ST_PREPARE.
     *
     * @return static
     */
    public static function prepare(): static
    {
        return (new static())->withValue(self::SCP_ST_PREPARE);
    }

    /**
     * Create a new instance pre-selected as SCP_ST_CONFIRM.
     *
     * @return static
     */
    public static function confirm(): static
    {
        return (new static())->withValue(self::SCP_ST_CONFIRM);
    }

    /**
     * Create a new instance pre-selected as SCP_ST_EXTERNALIZE.
     *
     * @return static
     */
    public static function externalize(): static
    {
        return (new static())->withValue(self::SCP_ST_EXTERNALIZE);
    }

    /**
     * Create a new instance pre-selected as SCP_ST_NOMINATE.
     *
     * @return static
     */
    public static function nominate(): static
    {
        return (new static())->withValue(self::SCP_ST_NOMINATE);
    }
}
