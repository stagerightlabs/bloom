<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class PaymentPathResource extends Resource
{
    /**
     * The type of asset being sent.  Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getAssetType(): ?string
    {
        return $this->payload->getString('asset_type');
    }

    /**
     * The code for the asset being sent. (The native asset has no code.)
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        return $this->payload->getString('asset_code');
    }

    /**
     * The address of the issuer of the asset being sent. (The native asset
     * has no issuer.)
     *
     * @return string|null
     */
    public function getAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('asset_issuer');
    }
}
