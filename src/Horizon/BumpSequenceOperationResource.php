<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class BumpSequenceOperationResource extends OperationResource
{
    /**
     * The new value for the source account's sequence number.
     *
     * @return string|null
     */
    public function getBumpTo(): ?string
    {
        return $this->payload->getString('bump_to');
    }
}
