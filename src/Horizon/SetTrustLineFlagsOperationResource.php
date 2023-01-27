<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class SetTrustLineFlagsOperationResource extends OperationResource
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
     * The code for the asset being trusted. (The native asset has no code.)
     *
     * @return string|null
     */
    public function getAssetCode(): ?string
    {
        return $this->payload->getString('asset_code');
    }

    /**
     * The address of the issuer of the asset being trusted. (The native asset
     * has no issuer.)
     *
     * @return string|null
     */
    public function getAssetIssuerAddress(): ?string
    {
        return $this->payload->getString('asset_issuer');
    }

    /**
     * The address of the trustor.
     *
     * @return string|null
     */
    public function getTrustorAddress(): ?string
    {
        return $this->payload->getString('trustor');
    }

    /**
     * The array of numeric values of flags that have been set.
     *
     * @return array<int>|null
     */
    public function getSetFlags(): ?array
    {
        return $this->payload->getArray('set_flags');
    }

    /**
     * The array of string values of flags that has been set.
     *
     * @return array<string>|null
     */
    public function getSetFlagsStrings(): ?array
    {
        return $this->payload->getArray('set_flags_s');
    }

    /**
     * The array of numeric values of flags that has been cleared.
     *
     * @return array<int>|null
     */
    public function getClearFlags(): ?array
    {
        return $this->payload->getArray('clear_flags');
    }

    /**
     * The array of string values of flags that has been cleared.
     *
     * @return array<string>|null
     */
    public function getClearFlagsStrings(): ?array
    {
        return $this->payload->getArray('clear_flags_s');
    }
}
