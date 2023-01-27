<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class ClawbackOperationResource extends OperationResource
{
    /**
     * The type of asset being clawed back.  Either `native`, `credit_alphanum4`,
     * or `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getAssetType(): ?string
    {
        return $this->payload->getString('asset_type');
    }

    /**
     * The code for the asset being clawed back. (The native asset has no code.)
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        return $this->payload->getString('asset_code');
    }

    /**
     * The address of the issuer of the asset being clawed back. (The native
     * asset has no issuer.)
     *
     * @return string|null
     */
    public function getAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('asset_issuer');
    }

    /**
     * The address of the account that held the assets that were clawed back.
     *
     * @return string|null
     */
    public function getFromAddress(): ?string
    {
        return $this->payload->getString('from');
    }

    /**
     * The amount of funds that were clawed back.
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
}
