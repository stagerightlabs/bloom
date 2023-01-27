<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class PathPaymentStrictSendOperationResource extends OperationResource
{
    /**
     * The type of asset being received. Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getAssetType(): ?string
    {
        return $this->payload->getString('asset_type');
    }

    /**
     * The code for the asset being received. (The native asset has no code.)
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        return $this->payload->getString('asset_code');
    }

    /**
     * The address of the issuer of the asset being received. (The native asset
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
     * Amount received designated in the destination asset.
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
     * The intermediary assets that this path hops through.
     *
     * @return array<PaymentPathResource>|null
     */
    public function getPath(): ?array
    {
        $path = $this->payload->getArray('path');

        if (empty($path)) {
            return null;
        }

        return array_map(function ($hop) {
            return PaymentPathResource::wrap($hop);
        }, $path);
    }

    /**
     * The amount sent designated in the source asset.
     *
     * @return ScaledAmount|null
     */
    public function getSourceAmount(): ?ScaledAmount
    {
        if ($amount = $this->payload->getString('source_amount')) {
            return ScaledAmount::of($amount);
        }

        return null;
    }

    /**
     * The amount sent designated in the source asset.
     *
     * @return ScaledAmount|null
     */
    public function getDestinationMinimum(): ?ScaledAmount
    {
        if ($min = $this->payload->getString('destination_min')) {
            return ScaledAmount::of($min);
        }

        return null;
    }

    /**
     * The type of asset being received. Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getSourceAssetType(): ?string
    {
        return $this->payload->getString('source_asset_type');
    }

    /**
     * The code for the source asset.
     *
     * @return string|null
     */
    public function getSourceAssetCode(): ?string
    {
        return $this->payload->getString('source_asset_code');
    }

    /**
     * The address of the issuer of the source asset.
     *
     * @return string|null
     */
    public function getSourceAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('source_asset_issuer');
    }
}
