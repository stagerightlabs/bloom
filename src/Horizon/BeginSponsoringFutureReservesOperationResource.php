<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class BeginSponsoringFutureReservesOperationResource extends OperationResource
{
    /**
     * The address of the account which will be sponsored.
     *
     * @return string|null
     */
    public function getSponsoredAccountAddress(): ?string
    {
        return $this->payload->getString('sponsored_id');
    }
}
