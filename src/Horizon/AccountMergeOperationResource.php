<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class AccountMergeOperationResource extends OperationResource
{
    /**
     * The address of the account being removed.
     *
     * @return string|null
     */
    public function getAccountAddress(): ?string
    {
        return $this->payload->getString('account');
    }

    /**
     * The address of the account receiving the deleted account's lumens.
     *
     * @return string|null
     */
    public function getIntoAddress(): ?string
    {
        return $this->payload->getString('into');
    }
}
