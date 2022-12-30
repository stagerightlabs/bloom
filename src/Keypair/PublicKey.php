<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

/**
 * An integer representation of the bytes that make up a public account address.
 */
class PublicKey extends Union implements XdrUnion, Addressable
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return PublicKeyType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            PublicKeyType::ED25519 => ED25519::class,
        ];
    }

    /**
     * Create a new PublicKey object from an address string.
     *
     * @param AccountId|PublicKey|Addressable|string $address
     * @param string $type
     * @return static
     */
    public static function fromAddressable(AccountId|PublicKey|Addressable|string $address, $type = PublicKeyType::ED25519): static
    {
        $publicKeyType = PublicKeyType::of($type);
        $decoded = $address instanceof Addressable
            ? StringKey::decodeAddress($address->getAddress())
            : StringKey::decodeAddress($address);

        if (!$decoded['valid']) {
            throw new InvalidArgumentException('Invalid public key address');
        }

        // For now there is only one valid key type...
        $key = ED25519::of($decoded['content']);

        $publicKey = new static();
        $publicKey->discriminator = $publicKeyType;
        $publicKey->value = $key;

        return $publicKey;
    }

    /**
     * Return the underlying ED25519 instance.
     *
     * @return ED25519|null
     *
     */
    public function unwrap(): ?ED25519
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the address as a string.
     *
     * @return string
     */
    public function getAddress(): string
    {
        if ($key = $this->unwrap()) {
            return StringKey::encodeAddress($key->getBytes());
        }

        return '';
    }

    /**
     * Return this public key as an AccountId.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return AccountId::fromAddressable($this->getAddress());
    }

    /**
     * Return this public key as a MuxedAccount.
     *
     * @return MuxedAccount
     */
    public function getMuxedAccount(): MuxedAccount
    {
        return MuxedAccount::fromAddressable($this->getAddress());
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
}
