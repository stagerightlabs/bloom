<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

interface Wallet extends Addressable, Signatory
{
    /**
     * Create a new wallet instance from an address or other addressable object.
     *
     * @param Addressable|string $address
     * @return static
     */
    public static function fromAddress(Addressable|string $address): static;

    /**
     * Create a new Wallet instance from a seed.
     *
     * @param string $seed
     * @return static
     */
    public static function fromSeed(string $seed): static;

    /**
     * Is this wallet able to sign transactions?
     *
     * @return bool
     */
    public function canSign(): bool;
}
