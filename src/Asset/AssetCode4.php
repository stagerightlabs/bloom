<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Asset;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class AssetCode4 implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 4;
    public string $value;

    /**
     * Create a new instance from a string.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(string $value): static
    {
        return (new static())->withCode($value);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->getCode(), XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withCode($xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Return the underlying string value.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->value;
    }

    /**
     * Set the value of the string.
     *
     * @param string $code
     * @throws InvalidArgumentException
     * @return static
     */
    public function withCode(string $code): static
    {
        $length = strlen($code);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for an asset code(4) where only {$max} bytes are allowed");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->value = $code;

        return $clone;
    }

    /**
     * Allow a hash object to be cast as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return isset($this->value)
            ? strval($this->value)
            : '';
    }

    /**
     * Allow an instance to be cast as a string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }
}
