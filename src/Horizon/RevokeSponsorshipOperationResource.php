<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class RevokeSponsorshipOperationResource extends OperationResource
{
    /**
     * The address of the account which is no longer sponsored.
     *
     * @return string|null
     */
    public function getAccountAddress(): ?string
    {
        return $this->payload->getString('account_id');
    }

    /**
     * The Id of the claimable balance which is no longer sponsored.
     *
     * @return string|null
     */
    public function getClaimableBalanceId(): ?string
    {
        return $this->payload->getString('claimable_balance_id');
    }

    /**
     * The address of the account whose data entry is no longer sponsored.
     *
     * @return string|null
     */
    public function getDataAccountAddress(): ?string
    {
        return $this->payload->getString('data_account_id');
    }

    /**
     * The name of the data entry which is no longer sponsored.
     *
     * @return string|null
     */
    public function getDataName(): ?string
    {
        return $this->payload->getString('data_name');
    }

    /**
     * The Id of the offer which is no longer sponsored.
     *
     * @return string|null
     */
    public function getOfferId(): ?string
    {
        return $this->payload->getString('offer_id');
    }

    /**
     * The address of the account whose trustline is no longer sponsored.
     *
     * @return string|null
     */
    public function getTrustlineAccountAddress(): ?string
    {
        return $this->payload->getString('trustline_account_id');
    }

    /**
     * The asset of the trustline which is no longer sponsored.
     *
     * @return string|null
     */
    public function getTrustlineAsset(): ?string
    {
        return $this->payload->getString('trustline_asset');
    }

    /**
     * The address of the signer which is no longer sponsored.
     *
     * @return string|null
     */
    public function getSignerAccountAddress(): ?string
    {
        return $this->payload->getString('signer_account_id');
    }

    /**
     * The type of the signer which is no longer sponsored.
     *
     * @return string|null
     */
    public function getSignerKey(): ?string
    {
        return $this->payload->getString('signer_key');
    }
}
