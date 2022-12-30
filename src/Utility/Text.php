<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

/**
 * Utility methods for common string manipulation tasks.
 */
class Text
{
    /**
     * Return the name of a class without a namespace prefix.
     *
     * @param class-string $class
     * @return string
     */
    public static function classBaseName(string $class): string
    {
        return (new \ReflectionClass($class))->getShortName();
    }

    /**
     * Convert a string to snake_case.
     *
     * @see https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
     * @param string $str
     * @return string
     */
    public static function snakeCase(string $str): string
    {
        return strtolower(strval(preg_replace('/(?<!^)[A-Z]/', '_$0', str_replace(' ', '', $str))));
    }
}
