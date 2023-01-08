<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

/**
 * @extends ResourceCollection<TransactionResource>
 */
class TransactionResourceCollection extends ResourceCollection
{
    /**
     * The type of resource that makes up this collection.
     *
     * @return class-string
     */
    protected function getResourceClass(): string
    {
        return TransactionResource::class;
    }
}
