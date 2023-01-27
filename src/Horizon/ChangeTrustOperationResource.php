<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class ChangeTrustOperationResource extends OperationResource
{
    /**
     * The type of asset being trusted.  Either `native`, `credit_alphanum4`,
     * `credit_alphanum12` or `liquidity_pool_shares`.
     *
     * @return string|null
     */
    public function getAssetType(): ?string
    {
        return $this->payload->getString('asset_type');
    }

    /**
     * The code for the asset being trusted. (Only present for credit assets.)
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        return $this->payload->getString('asset_code');
    }

    /**
     * The address of the issuer of the asset being trusted. (Only present
     * for credit assets.)
     *
     * @return string|null
     */
    public function getAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('asset_issuer');
    }

    /**
     * The maximum amount of the asset that the source account can hold.
     *
     * @return ScaledAmount|null
     */
    public function getLimit(): ?ScaledAmount
    {
        if ($limit = $this->payload->getString('limit')) {
            return ScaledAmount::of($limit);
        }

        return null;
    }

    /**
     * The issuing account. (Only present for credit assets.)
     *
     * @return string|null
     */
    public function getTrusteeAddress(): ?string
    {
        return $this->payload->getString('trustee');
    }

    /**
     * The source account.
     *
     * @return string|null
     */
    public function getTrustorAddress(): ?string
    {
        return $this->payload->getString('trustor');
    }

    /**
     * The liquidity pool whose trustline is being modified. (Only present
     * for asset_type == liquidity_pool_shares.)
     *
     * @return string|null
     */
    public function getLiquidityPoolId(): ?string
    {
        return $this->payload->getString('liquidity_pool_id');
    }
}
