<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

interface OperationVariety
{
    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool;

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string;
}
