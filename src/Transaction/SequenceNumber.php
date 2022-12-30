<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Int64;

class SequenceNumber extends Int64
{
    /**
     * Increment the current sequence number by one.
     *
     * @param int $bump
     * @return SequenceNumber
     */
    public function increment(int $bump = 1): SequenceNumber
    {
        return SequenceNumber::of($this->plus($bump));
    }
}
