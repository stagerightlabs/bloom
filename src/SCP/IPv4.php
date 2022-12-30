<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class IPv4 implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 4;
    public string $value;

    /**
     * Create a new instance via a static helper method.
     *
     * @param string $address
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(string $address): static
    {
        if (!$raw = inet_pton($address)) {
            throw new InvalidArgumentException("The value '{$address}'is not a valid IPv4 address");
        }

        return (new static())->withRaw($raw);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->value)) {
            throw new InvalidArgumentException('The IPv4 instance is missing an address');
        }

        $xdr->write($this->value, XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ipv4 = new static();
        $ipv4->value = $xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);

        return $ipv4;
    }

    /**
     * Return the raw value.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->value;
    }

    /**
     * Return the address.
     *
     * @throws UnexpectedValueException
     * @return string
     */
    public function getAddress(): string
    {
        if (!$address = inet_ntop($this->value)) {
            throw new UnexpectedValueException('The IPv4 instance contains an invalid address');
        }

        return $address;
    }

    /**
     * Set the value of the string.
     *
     * @param string $value
     * @return static
     */
    public function withRaw(string $value): static
    {
        $length = strlen($value);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for an IPv4 address where only {$max} bytes are allowed");
        }

        if ($length < $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for an IPv4 address where {$max} bytes are required");
        }

        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
