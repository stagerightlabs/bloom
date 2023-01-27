<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

/**
 * @extends ResourceCollection<OperationResource>
 */
class OperationResourceCollection extends ResourceCollection
{
    /**
     * The type of resource that makes up this collection.
     *
     * @return class-string
     */
    protected static function getResourceClass(): string
    {
        return OperationResource::class;
    }
}
