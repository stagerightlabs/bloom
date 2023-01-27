<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class ClawbackClaimableBalanceOperationResource extends OperationResource
{
    /**
     * The Id of the claimable balance.
     *
     * @return string|null
     */
    public function getBalanceId(): ?string
    {
        return $this->payload->getString('balance_id');
    }
}
