<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

/**
 * Prevent inherited classes from altering the constructor of a class.
 *
 * @codeCoverageIgnore
 */
trait NoConstructor
{
    final public function __construct()
    {
    }
}
