<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Utility\Url;

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

    /**
     * Return the link to the next page of transaction records, if available.
     *
     * @return string|null
     */
    public function getNextLink(): ?string
    {
        return $this->getLink('next');
    }

    /**
     * Return the paging token for the next page of transaction records, if available.
     *
     * @return string|null
     */
    public function getNextPagingToken(): ?string
    {
        return Url::parameter($this->getNextLink(), 'cursor');
    }

    /**
     * Return the link to the previous page of transaction records, if available.
     *
     * @return string|null
     */
    public function getPreviousLink(): ?string
    {
        return $this->getLink('prev');
    }

    /**
     * Return the paging token for the previous page of transaction records, if available.
     *
     * @return string|null
     */
    public function getPreviousPagingToken(): ?string
    {
        return Url::parameter($this->getPreviousLink(), 'cursor');
    }
}
