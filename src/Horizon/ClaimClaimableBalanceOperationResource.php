<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class ClaimClaimableBalanceOperationResource extends OperationResource
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

    /**
     * The address of the account which claimed the balance.
     *
     * @return string|null
     */
    public function getClaimantAddress(): ?string
    {
        return $this->payload->getString('claimant');
    }
}
