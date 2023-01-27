<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class ManageBuyOfferOperationResource extends OperationResource
{
    /**
     * The amount of `buying_asset` that the account making this offer is
     * willing to buy.
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
     * How many units of `buying_asset` it takes to get 1 unit of `selling_asset`.
     * A number string representing the decimal form of `price_r`.
     *
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->payload->getString('price');
    }

    /**
     * The numerator value for the precise representation of the buy and sell
     * price of the assets on offer.
     *
     * @return int|null
     */
    public function getPriceNumerator(): ?int
    {
        return $this->payload->getInteger('price_r.n');
    }

    /**
     * The denominator value the precise representation of the buy and sell
     * price of the assets on offer.
     *
     * @return int|null
     */
    public function getPriceDenominator(): ?int
    {
        return $this->payload->getInteger('price_r.d');
    }

    /**
     * The type for the buying asset. Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getBuyingAssetType(): ?string
    {
        return $this->payload->getString('buying_asset_type');
    }

    /**
     * The Stellar address of the buying asset’s issuer. Appears if the
     * `buying_asset_type` is not `native`.
     *
     * @return string|null
     */
    public function getBuyingAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('buying_asset_issuer');
    }

    /**
     * The code for the buying asset. Appears if the `buying_asset_type` is not
     * `native`.
     *
     * @return string|null
     */
    public function getBuyingAssetCode(): ?string
    {
        return $this->payload->getString('buying_asset_code');
    }

    /**
     * The type for the selling asset. Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
     *
     * @return string|null
     */
    public function getSellingAssetType(): ?string
    {
        return $this->payload->getString('selling_asset_type');
    }

    /**
     * The Stellar address of the selling asset’s issuer. Appears if the
     * `selling_asset_type` is not `native`.
     *
     * @return string|null
     */
    public function getSellingAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('selling_asset_issuer');
    }

    /**
     * The code for the selling asset. Appears if the `selling_asset_type` is not
     * `native`.
     *
     * @return string|null
     */
    public function getSellingAssetCode(): ?string
    {
        return $this->payload->getString('selling_asset_code');
    }

    /**
     * The unique identifier for this offer.
     *
     * @return string|null
     */
    public function getOfferId(): ?string
    {
        return $this->payload->getString('offer_id');
    }
}
