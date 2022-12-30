<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\UInt32;

class AccountSignerResource extends Resource
{
    /**
     * Return the numerical weight of this signer. Used to determine if a
     * transaction meets the threshold requirements.
     *
     * @return UInt32
     */
    public function getWeight(): UInt32
    {
        if ($weight = $this->payload->getInteger('weight')) {
            return UInt32::of($weight);
        }

        return UInt32::of(0);
    }

    /**
     * Return the account ID of the sponsor who is paying the reserves
     * for this signer.
     *
     * @return string|null
     */
    public function getSponsor(): ?string
    {
        return $this->payload->getString('sponsor');
    }

    /**
     * Return a hash of characters that is dependent on the signer type.
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->payload->getString('key');
    }

    /**
     * Return a string representing the signer type:
     *
     * - "ed25519_public_key"
     *   A normal Stellar public key
     *
     * - "sha256_hash"
     *   The SHA256 of some arbitrary 'X'.  Adding a signature of this type
     *   allows anyone who knows 'X' to sign a transaction from this account.
     *
     * - "preauth_tx"
     *   The hash of a pre-authorized transaction. The signer is automatically
     *   removed from the account when a matching transaction is applied.
     *
     * @see https://developers.stellar.org/api/resources/accounts/object/
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->payload->getString('type');
    }
}
