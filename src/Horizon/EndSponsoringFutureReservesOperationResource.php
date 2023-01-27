<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class EndSponsoringFutureReservesOperationResource extends OperationResource
{
    /**
     * The address of the account which initiated the sponsorship.
     *
     * @return string|null
     */
    public function getSponsorAddress(): ?string
    {
        return $this->payload->getString('begin_sponsor');
    }
}
