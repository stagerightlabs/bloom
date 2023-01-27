<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class ManageDataOperationResource extends OperationResource
{
    /**
     * The key for the managed data entry.
     *
     * @return string|null
     */
    public function getDataEntryName(): ?string
    {
        return $this->payload->getString('name');
    }

    /**
     * The value of the managed data entry.
     *
     * @return string|null
     */
    public function getDataEntryValue(): ?string
    {
        return $this->payload->getString('value');
    }
}
