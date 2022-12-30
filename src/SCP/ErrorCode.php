<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ErrorCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ERR_MISC = 'errMisc';
    public const ERR_DATA = 'errData';
    public const ERR_CONF = 'errConf';
    public const ERR_AUTH = 'errAuth';
    public const ERR_LOAD = 'errLoad';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::ERR_MISC, // unspecific error
            1 => self::ERR_DATA, // malformed data
            2 => self::ERR_CONF, // misconfiguration error
            3 => self::ERR_AUTH, // authentication failure
            4 => self::ERR_LOAD, // system overloaded
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
     * Create a new instance pre-selected as ERR_MISC.
     *
     * @return static
     */
    public static function misc(): static
    {
        return (new static())->withValue(self::ERR_MISC);
    }

    /**
     * Create a new instance pre-selected as ERR_DATA.
     *
     * @return static
     */
    public static function data(): static
    {
        return (new static())->withValue(self::ERR_DATA);
    }

    /**
     * Create a new instance pre-selected as ERR_CONF.
     *
     * @return static
     */
    public static function conf(): static
    {
        return (new static())->withValue(self::ERR_CONF);
    }

    /**
     * Create a new instance pre-selected as ERR_AUTH.
     *
     * @return static
     */
    public static function auth(): static
    {
        return (new static())->withValue(self::ERR_AUTH);
    }

    /**
     * Create a new instance pre-selected as ERR_LOAD.
     *
     * @return static
     */
    public static function load(): static
    {
        return (new static())->withValue(self::ERR_LOAD);
    }
}
