<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use StageRightLabs\Bloom\Account\Signatory;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Service;

final class KeypairService extends Service
{
    /**
     * Return a keypair from random bytes.
     *
     * @return Keypair
     */
    public function generate(): Keypair
    {
        return $this->fromPrivateKey(random_bytes(32));
    }

    /**
     * Return a keypair from a string of private key bytes.
     *
     * @param string $bytes
     * @return Keypair
     */
    public function fromPrivateKey(string $bytes): Keypair
    {
        if (strlen($bytes) != 32) {
            throw new InvalidArgumentException('Private key must be 32 bytes');
        }

        return new Keypair(seed: StringKey::encodeSeed($bytes));
    }

    /**
     * Return a keypair from a string of public key bytes.
     *
     * @param string $bytes
     * @return Keypair
     */
    public function fromPublicKey(string $bytes): Keypair
    {
        if (strlen($bytes) != 32) {
            throw new InvalidArgumentException('Private key must be 32 bytes');
        }

        return new Keypair(address: StringKey::encodeAddress($bytes));
    }

    /**
     * Return a keypair from a seed.
     *
     * @param string $seed
     * @return Keypair
     */
    public function fromSeed(string $seed): Keypair
    {
        $validity = StringKey::checkValidity($seed, StringKey::TYPE_SEED);

        if (!$validity['valid']) {
            throw new InvalidKeyException("Attempting to use invalid seed in a keypair: {$validity['message']}");
        }

        return new Keypair(seed: $seed);
    }

    /**
     * Return a keypair from an address.
     *
     * @param string $address
     * @return Keypair
     */
    public function fromAddress(string $address): Keypair
    {
        $validity = StringKey::checkValidity($address, StringKey::TYPE_ADDRESS);

        if (!$validity['valid']) {
            throw new InvalidKeyException("Attempting to use invalid address in keypair: {$validity['message']}");
        }

        return new Keypair(address: $address);
    }

    /**
     * Determine whether or not a keypair is capable of signing.
     *
     * @param Keypair $keypair
     * @return bool
     */
    public function canSign(Keypair $keypair): bool
    {
        return !empty($keypair->getPrivateKey());
    }

    /**
     * Sign a payload with a qualified signatory.
     *
     * @param Signatory $signer
     * @param string $message
     * @throws InvalidKeyException
     * @return Signature|null
     */
    public function sign(Signatory $signer, string $message): ?Signature
    {
        return $signer->sign($message);
    }
}
