<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class Curve25519Public implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 32;
    public string $raw;

    /**
     * Create a new instance from a native string.
     *
     * @param string $value
     * @return static
     */
    public static function of(string $value): static
    {
        return (new static())->withRaw($value);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->raw)) {
            throw new InvalidArgumentException('The Curve25519Public is missing a value');
        }

        $xdr->write($this->raw, XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $curve25519Public = new static();
        $curve25519Public->raw = $xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);

        return $curve25519Public;
    }

    /**
     * Return the underlying value.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return trim($this->raw);
    }

    /**
     * Set the value of the string.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public function withRaw(string $value): static
    {
        $length = strlen($value);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for Curve25519Public where only {$max} bytes are allowed");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->raw = $value;

        return $clone;
    }

    /**
     * Allow an instance to be cast as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return isset($this->raw)
            ? strval($this->raw)
            : '';
    }

    /**
     * Return the curve 25519 secret as a string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }
}
