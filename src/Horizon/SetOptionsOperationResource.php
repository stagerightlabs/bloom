<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class SetOptionsOperationResource extends OperationResource
{
    /**
     * The public key of the new signer.
     *
     * @return string|null
     */
    public function getSignerKey(): ?string
    {
        return $this->payload->getString('signer_key');
    }

    /**
     * The weight of the new signer. Can range from 1 to 255.
     *
     * @return int|null
     */
    public function getSignerWeight(): ?int
    {
        return $this->payload->getInteger('signer_weight');
    }

    /**
     * The weight of the master key. Can range from 1 to 255.
     *
     * @return int|null
     */
    public function getMasterKeyWeight(): ?int
    {
        return $this->payload->getInteger('master_key_weight');
    }

    /**
     * The sum weight for the low threshold.
     *
     * @return int|null
     */
    public function getLowThreshold(): ?int
    {
        return $this->payload->getInteger('low_threshold');
    }

    /**
     * The sum weight for the medium threshold.
     *
     * @return int|null
     */
    public function getMediumThreshold(): ?int
    {
        return $this->payload->getInteger('med_threshold');
    }

    /**
     * The sum weight for the high threshold.
     *
     * @return int|null
     */
    public function getHighThreshold(): ?int
    {
        return $this->payload->getInteger('high_threshold');
    }

    /**
     * The home domain used for stellar.toml file discovery.
     *
     * @return string|null
     */
    public function getHomeDomain(): ?string
    {
        return $this->payload->getString('home_domain');
    }

    /**
     * The array of numeric values of flags that have been set in this operation.
     * Options include 1 for `AUTH_REQUIRED_FLAG`, 2 for `AUTH_REVOCABLE_FLAG`,
     * and 4 for `AUTH_IMMUTABLE_FLAG`.
     *
     * @return array<int>|null
     */
    public function getSetFlags(): ?array
    {
        return $this->payload->getArray('set_flags');
    }

    /**
     * The array of string values of flags that has been set in this operation.
     * Options include `AUTH_REQUIRED_FLAG`, `AUTH_REVOCABLE_FLAG`, and
     * `AUTH_IMMUTABLE_FLAG`.
     *
     * @return array<string>|null
     */
    public function getSetFlagsStrings(): ?array
    {
        return $this->payload->getArray('set_flags_s');
    }

    /**
     * The array of numeric values of flags that has been cleared in this
     * operation. Options include 1 for `AUTH_REQUIRED_FLAG`, 2 for
     * `AUTH_REVOCABLE_FLAG`, and 4 for `AUTH_IMMUTABLE_FLAG`.
     *
     * @return array<int>|null
     */
    public function getClearFlags(): ?array
    {
        return $this->payload->getArray('clear_flags');
    }

    /**
     * The array of string values of flags that has been cleared in this operation.
     * Options include `AUTH_REQUIRED_FLAG`, `AUTH_REVOCABLE_FLAG`, and
     * `AUTH_IMMUTABLE_FLAG`.
     *
     * @return array<string>|null
     */
    public function getClearFlagsStrings(): ?array
    {
        return $this->payload->getArray('clear_flags_s');
    }
}
