<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

trait NoChanges
{
    // Prevent the ability to modify class properties via __set()
    public function __set(string $id, mixed $val): void
    {
        return;
    }

    // Prevent the ability to modify class properties via __unset()
    public function __unset(string $name): void
    {
        return;
    }
}
