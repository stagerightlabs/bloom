<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\MuxedAccountMed25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Keypair\CryptoKeyType;
use StageRightLabs\Bloom\Keypair\StringKey;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class MuxedAccount extends Union implements XdrUnion, Addressable
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return CryptoKeyType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            CryptoKeyType::KEY_TYPE_ED25519       => ED25519::class,
            CryptoKeyType::KEY_TYPE_MUXED_ED25519 => MuxedAccountMed25519::class,
        ];
    }

    /**
     * Build a new instance from an address key.
     *
     * @param Addressable|string $address
     * @return static
     */
    public static function fromAddressable(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        return StringKey::isValidMuxedAddress($address)
            ? self::wrapMuxedEd25519($address)
            : self::wrapEd25519($address);
    }

    /**
     * Create a MuxedAccount object from a muxed address.
     *
     * @param MuxedAccountMed25519|Addressable|string $address
     * @return static
     */
    public static function wrapMuxedEd25519(MuxedAccountMed25519|Addressable|string $address): static
    {
        $muxedAccount = new static();
        $muxedAccount->discriminator = CryptoKeyType::muxedEd25519();
        $muxedAccount->value = $address instanceof MuxedAccountMed25519
            ? $address
            : MuxedAccountMed25519::fromMuxedAddress($address);

        return $muxedAccount;
    }

    /**
     * Create a MuxedAccount object from a regular address.
     *
     * @param ED25519|Addressable|string $address
     * @return static
     */
    public static function wrapEd25519(ED25519|Addressable|string $address): static
    {
        $muxedAccount = new static();
        $muxedAccount->discriminator = CryptoKeyType::ed25519();
        $muxedAccount->value = $address instanceof ED25519
            ? $address
            : ED25519::fromAddress($address);

        return $muxedAccount;
    }

    /**
     * Return the underlying addressable object.
     *
     * @return ED25519|MuxedAccountMed25519|null
     */
    public function unwrap(): ED25519|MuxedAccountMed25519|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the address string.
     *
     * @return string
     */
    public function getAddress(): string
    {
        if ($address = $this->unwrap()) {
            return $address->getAddress();
        }

        return '';
    }

    /**
     * Return the address as an AccountId.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return AccountId::fromAddressable($this->getAddress());
    }

    /**
     * Clone the address into a new MuxedAccount object.
     *
     * This is only needed here to satisfy the 'addressable' interface.
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
