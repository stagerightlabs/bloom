<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

class ExtensionPoint extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return XDR::INT;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            0 => XDR::VOID,
        ];
    }

    /**
     * Create an empty instance.
     *
     * @return static
     */
    public static function empty(): static
    {
        $extensionPoint = new static();
        $extensionPoint->discriminator = 0;
        $extensionPoint->value = null;

        return $extensionPoint;
    }

    /**
     * Return the underlying value.
     *
     * @return null
     */
    public function unwrap()
    {
        return null;
    }
}
