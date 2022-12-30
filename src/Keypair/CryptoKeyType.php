<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class CryptoKeyType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const KEY_TYPE_ED25519 = 'keyTypeEd25519';
    public const KEY_TYPE_PRE_TX_AUTH = 'keyTypePreAuthTx';
    public const KEY_TYPE_HASH_X = 'keyTypeHashX';
    public const KEY_TYPE_MUXED_ED25519 = 'keyTypeMuxedEd25519';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::KEY_TYPE_ED25519,
            1   => self::KEY_TYPE_PRE_TX_AUTH,
            2   => self::KEY_TYPE_HASH_X,
            256 => self::KEY_TYPE_MUXED_ED25519,
        ];
    }

    /**
     * Create a new instance pre-selected as 'keyTypeEd25519'.
     *
     * @return static
     */
    public static function ed25519(): static
    {
        return (new static())->withValue(self::KEY_TYPE_ED25519);
    }

    /**
     * Create a new instance pre-selected as 'keyTypePreAuthTx'.
     *
     * @return static
     */
    public static function preAuthTx(): static
    {
        return (new static())->withValue(self::KEY_TYPE_PRE_TX_AUTH);
    }

    /**
     * Create a new instance pre-selected as 'keyTypeHashX'.
     *
     * @return static
     */
    public static function hashX(): static
    {
        return (new static())->withValue(self::KEY_TYPE_HASH_X);
    }

    /**
     * Create a new instance pre-selected as 'keyTypeMuxedEd25519'.
     *
     * @return static
     */
    public static function muxedEd25519(): static
    {
        return (new static())->withValue(self::KEY_TYPE_MUXED_ED25519);
    }
}
