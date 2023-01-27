<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;

class AccountBalanceResource extends Resource
{
    /**
     * Return the asset balance as a scaled amount.
     *
     * @return ScaledAmount
     */
    public function getBalance(): ScaledAmount
    {
        $balance = $this->payload->getString('balance') ?? '0';

        return ScaledAmount::of($balance);
    }

    /**
     * Return the asset balance as a descaled Int64.
     *
     * @return Int64
     */
    public function getDescaledBalance(): Int64
    {
        if ($balance = $this->payload->getString('balance')) {
            return ScaledAmount::of($balance)->descale();
        }

        return Int64::of(0);
    }

    /**
     * Return the sum of all buy offers owned by the account for this asset.
     *
     * @return ScaledAmount
     */
    public function getBuyingLiabilities(): ScaledAmount
    {
        $buyingLiabilities = $this->payload->getString('buying_liabilities') ?? '0';

        return ScaledAmount::of($buyingLiabilities);
    }

    /**
     * Return the sum of all buy offers owned by the account for this asset
     * as a descaled Int64.
     *
     * @return Int64
     */
    public function getDescaledBuyingLiabilities(): Int64
    {
        if ($buyingLiabilities = $this->payload->getString('buying_liabilities')) {
            return ScaledAmount::of($buyingLiabilities)->descale();
        }

        return Int64::of(0);
    }

    /**
     * Return the sum of all sell offers owned by this account for this asset.
     *
     * @return ScaledAmount
     */
    public function getSellingLiabilities(): ScaledAmount
    {
        $sellingLiabilities = $this->payload->getString('selling_liabilities') ?? '0';

        return ScaledAmount::of($sellingLiabilities);
    }

    /**
     * Return the sum of all sell offers owned by this account for this asset,
     * as a descaled Int64.
     *
     * @return Int64
     */
    public function getDescaledSellingLiabilities(): Int64
    {
        if ($sellingLiabilities = $this->payload->getString('selling_liabilities')) {
            return ScaledAmount::of($sellingLiabilities)->descale();
        }

        return Int64::of(0);
    }

    /**
     * Return the maximum amount of this asset that this account is willing to
     * accept.
     *
     * @return ScaledAmount
     */
    public function getLimit(): ScaledAmount
    {
        $limit = $this->payload->getString('limit') ?? '922337203685.4775807';

        return ScaledAmount::of($limit);
    }

    /**
     * Return the maximum amount of this asset that this account is willing to
     * accept, as a descaled Int64.
     *
     * @return Int64
     */
    public function getDescaledLimit(): Int64
    {
        if ($limit = $this->payload->getString('limit')) {
            return ScaledAmount::of($limit)->descale();
        }

        return Int64::of('9223372036854775807');
    }

    /**
     * Return the type of asset represented by this balance.
     * Either "native", "credit_alphanum4", "credit_alphanum12" or
     * "liquidity_pool_shares".
     *
     * @return string
     */
    public function getAssetType(): ?string
    {
        return $this->payload->getString('asset_type') ?? '';
    }

    /**
     * Does this balance represent the native asset?
     *
     * @return bool
     */
    public function isNativeAsset(): bool
    {
        return $this->getAssetType() == 'native';
    }

    /**
     * Return the asset code as a string, if present.
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        if ($this->isNativeAsset()) {
            return Bloom::NATIVE_ASSET_CODE;
        }

        return $this->payload->getString('asset_code');
    }

    /**
     * Get the stellar address of the asset's issuer, if present.
     *
     * @return string|null
     */
    public function getAssetIssuer(): ?string
    {
        return $this->payload->getString('asset_issuer');
    }

    /**
     * Get the account ID of the sponsor who is paying the reserves for
     * this trustline, if present.
     *
     * @return string|null
     */
    public function getSponsor(): ?string
    {
        return $this->payload->getString('sponsor');
    }

    /**
     * It returns a unique asset identifier in the form of 'code:issuer'.
     *
     * @return string
     */
    public function getCanonicalAssetName(): ?string
    {
        if ($this->getAssetType() == 'native') {
            return 'native';
        }

        $identifier = $this->getAssetCode();

        return $this->getAssetIssuer()
            ? $identifier . ':' . $this->getAssetIssuer()
            : null;
    }
}
