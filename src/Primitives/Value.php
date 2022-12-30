<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class Value implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public string $value;

    /**
     * Create a new instance from a string.
     *
     * @param string $value
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
        $xdr->write($this->value, XDR::OPAQUE_VARIABLE);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withValue($xdr->read(XDR::OPAQUE_VARIABLE));
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
     * Return the underlying value.
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
     * @return static
     */
    public function withValue(string $value): static
    {
        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
