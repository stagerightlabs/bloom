<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class SignerKey extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return SignerKeyType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            SignerKeyType::SIGNER_KEY_ED25519                => ED25519::class,
            SignerKeyType::SIGNER_KEY_PRE_AUTH_TX            => UInt256::class,
            SignerKeyType::SIGNER_KEY_HASH_X                 => UInt256::class,
            SignerKeyType::SIGNER_KEY_ED25519_SIGNED_PAYLOAD => SignerKeyEd25519SignedPayload::class,
        ];
    }

    /**
     * Create a new instance from an ED25519 signature.
     *
     * @param ED25519 $ed25519
     * @return static
     */
    public static function wrapEd25519(ED25519 $ed25519): static
    {
        $signerKey = new static();
        $signerKey->discriminator = SignerKeyType::ed25519();
        $signerKey->value = $ed25519;

        return $signerKey;
    }

    /**
     * Create a new instance from a pre auth tx.
     *
     * @return static
     */
    public static function wrapPreAuthTx(UInt256 $preAuthTx): static
    {
        $signerKey = new static();
        $signerKey->discriminator = SignerKeyType::preAuthTx();
        $signerKey->value = $preAuthTx;

        return $signerKey;
    }

    /**
     * Create a new instance from a hash.
     *
     * @param UInt256 $hashX
     * @return static
     */
    public static function wrapHashX(UInt256 $hashX): static
    {
        $signerKey = new static();
        $signerKey->discriminator = SignerKeyType::hashX();
        $signerKey->value = $hashX;

        return $signerKey;
    }

    /**
     * Create a new instance from a signed Ed25519 payload.
     *
     * @param SignerKeyEd25519SignedPayload $signedPayload
     * @return static
     */
    public static function wrapEd25519SignedPayload(SignerKeyEd25519SignedPayload $signedPayload): static
    {
        $signerKey = new static();
        $signerKey->discriminator = SignerKeyType::ed25519SignedPayload();
        $signerKey->value = $signedPayload;

        return $signerKey;
    }

    /**
     * Return the underlying signer key.
     *
     * @return ED25519|UInt256|SignerKeyEd25519SignedPayload|null
     */
    public function unwrap(): ED25519|UInt256|SignerKeyEd25519SignedPayload|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
