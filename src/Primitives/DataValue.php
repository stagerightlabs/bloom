<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class DataValue implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 64;
    public string $raw;

    /**
     * Create a new instance from a string.
     *
     * @param DataValue|string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(DataValue|string $value): static
    {
        if ($value instanceof DataValue) {
            $value = $value->getRaw();
        }

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
        $xdr->write($this->getRaw(), XDR::OPAQUE_VARIABLE, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withRaw($xdr->read(XDR::OPAQUE_VARIABLE, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Return the underlying value.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
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
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for an data value where only {$max} bytes are allowed");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->raw = $value;

        return $clone;
    }

    /**
     * Allow this class to be cast to a native string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getRaw();
    }

    /**
     * Return this value as a native string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->getRaw();
    }
}
