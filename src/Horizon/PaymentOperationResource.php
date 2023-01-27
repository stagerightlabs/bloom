<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class PaymentOperationResource extends OperationResource
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

    /**
     * The payment sender's address.
     *
     * @return string|null
     */
    public function getFromAddress(): ?string
    {
        return $this->payload->getString('from');
    }

    /**
     * The payment recipient's address.
     *
     * @return string|null
     */
    public function getToAddress(): ?string
    {
        return $this->payload->getString('to');
    }

    /**
     * The amount sent.
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
