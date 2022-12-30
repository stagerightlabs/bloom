<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class SignerKeyType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SIGNER_KEY_ED25519 = 'signerKeyTypeEd25519';
    public const SIGNER_KEY_PRE_AUTH_TX = 'signerKeyTypePreAuthTx';
    public const SIGNER_KEY_HASH_X = 'signerKeyTypeHashX';
    public const SIGNER_KEY_ED25519_SIGNED_PAYLOAD = 'signerKeyTypeEd25519SignedPayload';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::SIGNER_KEY_ED25519,
            1 => self::SIGNER_KEY_PRE_AUTH_TX,
            2 => self::SIGNER_KEY_HASH_X,
            3 => self::SIGNER_KEY_ED25519_SIGNED_PAYLOAD,
        ];
    }

    /**
     * Return the selected signer key type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance that is pre-selected as SIGNER_KEY_ED25519.
     *
     * @return static
     */
    public static function ed25519(): static
    {
        return (new static())->withValue(self::SIGNER_KEY_ED25519);
    }

    /**
     * Create a new instance that is pre-selected as SIGNER_KEY_PRE_AUTH_TX.
     *
     * @return static
     */
    public static function preAuthTx(): static
    {
        return (new static())->withValue(self::SIGNER_KEY_PRE_AUTH_TX);
    }

    /**
     * Create a new instance that is pre-selected as SIGNER_KEY_HASH.
     *
     * @return static
     */
    public static function hashX(): static
    {
        return (new static())->withValue(self::SIGNER_KEY_HASH_X);
    }

    /**
     * Create a new instance that is pre-selected as SIGNER_KEY_ED25519_SIGNED_PAYLOAD.
     *
     * @return static
     */
    public static function ed25519SignedPayload(): static
    {
        return (new static())->withValue(self::SIGNER_KEY_ED25519_SIGNED_PAYLOAD);
    }
}
