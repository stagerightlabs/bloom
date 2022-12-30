<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

use DeepCopy\DeepCopy;

class Copy
{
    /**
     * Use the deep_copy tool to create a complete copy.
     *
     * @see https://github.com/myclabs/DeepCopy
     * @template T
     * @param T $source
     * @return T
     */
    public static function deep(mixed $source)
    {
        static $copier = null;

        // @codeCoverageIgnoreStart
        if (null === $copier) {
            $copier = new DeepCopy(true);
        }
        // @codeCoverageIgnoreEnd

        return $copier->copy($source);
    }
}
