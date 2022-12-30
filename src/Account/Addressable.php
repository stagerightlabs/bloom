<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Primitives\UInt32;

interface Addressable
{
    /**
     * Get the appropriate public address as a string. Regular accounts will
     * have an address that starts with a 'G' and muxed accounts will have
     * an address that starts with an 'M'.
     *
     * @return string
     */
    public function getAddress(): string;

    /**
     * Convert the address to an AccountId object.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId;

    /**
     * Convert the address to a MuxedAccount object.
     *
     * @return MuxedAccount
     */
    public function getMuxedAccount(): MuxedAccount;

    /**
     * Convert this addressable into a weighted signer for account management.
     *
     * @param UInt32|integer $weight
     * @return Signer
     */
    public function getWeightedSigner(UInt32|int $weight): Signer;
}
