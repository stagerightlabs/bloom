<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

interface Hashable
{
    /**
     * Derive a SHA256 hash of this object.
     *
     * @return Hash
     */
    public function getHash(): Hash;
}
