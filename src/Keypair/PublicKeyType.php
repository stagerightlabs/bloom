<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Keypair;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

class PublicKeyType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ED25519 = 'publicKeyTypeEd25519';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::ED25519,
        ];
    }

    /**
     * Create a new instance pre-selected as ED25519.
     *
     * @return static
     */
    public static function ed25519(): static
    {
        return (new static())->withValue(self::ED25519);
    }
}
