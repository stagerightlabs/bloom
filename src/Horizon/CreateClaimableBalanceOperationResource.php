<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class CreateClaimableBalanceOperationResource extends OperationResource
{
    /**
     * The asset available to be claimed in the SEP-11 form
     * asset_code:issuing_address or native (for XLM)
     *
     * @see https://github.com/stellar/stellar-protocol/blob/0c675fb3a482183dcf0f5db79c12685acf82a95c/ecosystem/sep-0011.md#values
     * @return string|null
     */
    public function getAsset(): ?string
    {
        return $this->payload->getString('asset');
    }

    /**
     * The amount available to be claimed.
     *
     * @return ScaledAmount|null
     */
    public function getAmount(): ?ScaledAmount
    {
        if ($amount = $this->payload->getString('amount')) {
            return ScaledAmount::of($amount);
        }

        return null;
    }

    /**
     * The list of entries which could claim the claimable balance.
     *
     * @return array<ClaimantResource>
     */
    public function getClaimants(): array
    {
        return array_map(function ($claimant) {
            return ClaimantResource::wrap($claimant);
        }, $this->payload->getArray('claimants') ?? []);
    }
}
