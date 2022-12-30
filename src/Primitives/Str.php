<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

abstract class Str implements XdrTypedef
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected string $value;

    /**
     * What is the maximum number of bytes allowed for this string?
     *
     * @return int
     */
    abstract public function maxByteLength(): int;

    /**
     * Create a new instance from a string.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(string $value): static
    {
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
        $xdr->write($this->value, XDR::STRING);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return self::of($xdr->read(XDR::STRING));
    }

    /**
     * Allow this class to be cast to a native string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Return this value as a native string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }

    /**
     * Get the string value.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
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
        $max = $this->maxByteLength();

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string where only {$max} bytes are allowed");
        }

        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
