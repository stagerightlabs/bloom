<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class Hash implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 32;
    public string $value;

    /**
     * Create a new instance by hashing a string.
     * If given a hash, create a clone.
     *
     * @param Hash|string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public static function make(Hash|string $value): static
    {
        if ($value instanceof Hash) {
            return self::wrap($value->toNativeString());
        }

        return self::sha256($value);
    }

    /**
     * Create a new instance from a string without modifying the value.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public static function wrap(Hash|string $value): static
    {
        if ($value instanceof Hash) {
            return self::wrap($value->toNativeString());
        }

        return (new static())->withValue($value);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->toNativeString(), XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withValue($xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Create a new hash object by running a data payload through SHA256.
     *
     * @param string $data
     * @return static
     */
    public static function sha256(string $data): static
    {
        return (new static())->withValue(hash('sha256', $data, true));
    }

    /**
     * Return the hash value as a hexadecimal string.
     *
     * @return string
     */
    public function toHex(): string
    {
        return bin2hex($this->toNativeString());
    }

    /**
     * Create a new instance from a hex string.
     *
     * @param Hash|string $hex
     * @return static
     */
    public static function fromHex(Hash|string $hex): static
    {
        if ($hex instanceof Hash) {
            $hex = $hex->toHex();
        }

        // Remove the extra leading zeros if present
        $stripped = ltrim($hex, '0');

        // Ensure the stripped value is still 64 characters long
        // (we may have removed too many zeros)
        if (strlen($stripped) < 64) {
            $stripped = str_pad($stripped, 64, '0', STR_PAD_LEFT);
        }

        return self::wrap(strval(hex2bin($stripped)));
    }

    /**
     * Return the hash object as a string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }

    /**
     * Allow a hash object to be cast as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return strval($this->value);
    }

    /**
     * Set the value of the string.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public function withValue(string $value): static
    {
        $length = strlen($value);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for a hash where only {$max} bytes are allowed");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
