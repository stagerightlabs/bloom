<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class AllowTrustOperationResource extends OperationResource
{
    /**
     * The type of asset being trusted.  Either `native`, `credit_alphanum4`, or
     * `credit_alphanum12`.
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
     * Flag indicating whether the trustline is authorized. 0 if the account is
     * not authorized to transact with the asset in any way. 1 if the account
     * is authorized to transact with the asset. 2 if the account is able
     * to maintain orders, but not to perform other transactions.
     *
     * @return int|null
     */
    public function getAuthorizedFlag(): ?int
    {
        return $this->payload->getInteger('authorize');
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
}
