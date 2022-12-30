<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;

class String28 extends Str implements XdrTypedef
{
    /**
     * What is the maximum number of bytes allowed for this string?
     *
     * @return int
     */
    public function maxByteLength(): int
    {
        return 28;
    }
}
