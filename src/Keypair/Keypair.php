<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use SodiumException;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Account\Wallet;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Primitives\UInt32;

final class Keypair implements Wallet
{
    /**
     * Instantiate a new Keypair instance.
     *
     * @param string $seed          A base-32 string representing a private key;
     *                              must be prefixed with 'S'
     *                              a.k.a. the account secret
     * @param string $address       A base-32 string representing a public key;
     *                              must be prefixed with 'G' or 'M',
     *                              a.k.a. the account address
     * @param string $publicKey     A string containing the raw bytes of the
     *                              public key
     * @param string $privateKey    A string containing the raw bytes of the
     *                              private key
     */
    public function __construct(
        protected string $address = '',
        protected string $seed = '',
        protected string $publicKey = '',
        protected string $privateKey = '',
    ) {
        // Attempt to set the seed if we have the private key
        if (empty($this->seed) && !empty($this->privateKey)) {
            $this->seed = StringKey::encodeSeed($this->privateKey);
        }

        // Attempt to set the address if it was not provided
        if (empty($this->address) && !empty($this->seed)) {
            $this->address = StringKey::addressFromSeed($this->seed);
        }

        // Attempt to set the private key if it was not provided
        if (empty($this->privateKey) && !empty($this->seed)) {
            $this->privateKey =
                StringKey::decodeOrFail($this->seed, StringKey::TYPE_SEED);
        }

        // Attempt to set the public key if it was not provided
        if (empty($this->publicKey) && !empty($this->address)) {
            $this->publicKey =
                StringKey::decodeOrFail($this->address, StringKey::TYPE_ADDRESS);
        }

        // Attempt to set the address if we have the public key
        if (empty($this->address) && empty($this->seed) && !empty($this->publicKey)) {
            $this->address = StringKey::encodeAddress($this->publicKey);
        }
    }

    /**
     * Attempt to sign a message using a private key.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @throws SodiumException
     * @return Signature
     */
    public function sign(string $message): Signature
    {
        if (!$this->canSign()) {
            $exception = (new InvalidKeyException('The keypair is missing a private key which is required for signing'))
                ->setKey($this->getAddress());
            throw $exception;
        }

        $raw = $this->getRaw();
        // @codeCoverageIgnoreStart
        if ($raw == '') {
            $exception = (new InvalidKeyException('The keypair does not have a valid private key'))
                ->setKey($this->getAddress());
            throw $exception;
        }
        // @codeCoverageIgnoreEnd

        $secretKey = sodium_crypto_sign_secretkey($raw);

        return Signature::of(sodium_crypto_sign_detached($message, $secretKey));
    }

    /**
     * Attempt to sign a message using a private key,
     * returning a decorated signature.
     *
     * @param string $message
     * @throws InvalidKeyException
     * @throws SodiumException
     * @return DecoratedSignature
     */
    public function signDecorated(string $message): DecoratedSignature
    {
        return $this->decorate($this->sign($message));
    }

    /**
     * Verify a signature.
     *
     * @param string $message
     * @param Signature|string $signature
     * @throws InvalidKeyException
     * @throws SodiumException
     * @return bool
     */
    public function verify(string $message, Signature|string $signature): bool
    {
        if (!$this->canSign()) {
            $exception = (new InvalidKeyException('The keypair is missing a private key which is required for signature verification'))
                ->setKey($this->getAddress());
            throw $exception;
        }

        if ($signature instanceof Signature) {
            $signature = $signature->getRaw();
        }

        // @codeCoverageIgnoreStart
        if ($signature == '') {
            throw new InvalidKeyException("Cannot validate an invalid signature");
        }

        $raw = $this->getRaw();
        if ($raw == '') {
            $exception = (new InvalidKeyException('The keypair does not have a valid private key'))
                ->setKey($this->getAddress());
            throw $exception;
        }
        // @codeCoverageIgnoreEnd

        $publicKey = sodium_crypto_sign_publickey($raw);

        return sodium_crypto_sign_verify_detached($signature, $message, $publicKey);
    }

    /**
     * Wrap a signature in a new DecoratedSignature instance.
     *
     * @param Signature $signature
     * @return DecoratedSignature
     */
    public function decorate(Signature $signature): DecoratedSignature
    {
        // Return the decorated signature
        return (new DecoratedSignature())
            ->withHint($this->getSignatureHint())
            ->withSignature($signature);
    }

    /**
     * Return the last four characters of the address.
     *
     * @return SignatureHint
     */
    public function getSignatureHint(): SignatureHint
    {
        return SignatureHint::of(substr($this->getPublickey(), -4));
    }

    /**
     * Instantiate a Keypair object from an address.
     *
     * @param Addressable|string $address
     * @return static
     */
    public static function fromAddress(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        return new Keypair(address: $address);
    }

    /**
     * Instantiate a Keypair object from a seed.
     *
     * @param string $seed
     * @return static
     */
    public static function fromSeed(string $seed): static
    {
        return new Keypair(seed: $seed);
    }

    /**
     * Return the address as an AccountId object.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return AccountId::fromAddressable($this->address);
    }

    /**
     * Return the address as a MuxedAccount object.
     *
     * @return MuxedAccount
     */
    public function getMuxedAccount(): MuxedAccount
    {
        return MuxedAccount::fromAddressable($this->address);
    }

    /**
     * Convert this addressable into a weighted signer for account management.
     *
     * @param UInt32|integer $weight
     * @return Signer
     */
    public function getWeightedSigner(UInt32|int $weight): Signer
    {
        $signerKey = SignerKey::wrapEd25519(
            ED25519::fromAddress($this->getAddress())
        );

        return Signer::of($signerKey, $weight);
    }

    /**
     * Can this keypair do signing?
     *
     * @return bool
     */
    public function canSign(): bool
    {
        return !empty($this->privateKey);
    }

    /**
     * Return the seed.
     *
     * @return string
     */
    public function getSeed(): string
    {
        return $this->seed;
    }

    /**
     * Return the address.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * Return the public key as a string of raw bytes.
     *
     * @return string
     */
    public function getPublickey(): string
    {
        return $this->publicKey;
    }

    /**
     * Return the private key as a string of raw bytes.
     *
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * Return the keypair as a string of raw bytes. Attempting to use this
     * method without an available private key will throw an exception.
     *
     * @throws InvalidKeyException
     * @return string
     */
    public function getRaw(): string
    {
        if (!$this->canSign()) {
            $exception = (new InvalidKeyException('The keypair is missing a required private key'))
                ->setKey($this->getAddress());
            throw $exception;
        }

        $privateKey = $this->getPrivateKey();

        // @codeCoverageIgnoreStart
        if ($privateKey == '') {
            $exception = (new InvalidKeyException('The keypair has an invalid private key'))
                ->setKey($this->getAddress());
            throw $exception;
        }
        // @codeCoverageIgnoreEnd

        return sodium_crypto_sign_seed_keypair($privateKey);
    }
}
