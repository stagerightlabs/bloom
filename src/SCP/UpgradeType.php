<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class UpgradeType implements XdrTypedef
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_LENGTH = 128;
    protected string $value;

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
        return self::of($xdr->read(XDR::OPAQUE_VARIABLE));
    }

    /**
     * Allow an instance of this class to be cast to a native string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Return the value as a native string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }

    /**
     * Get the value as a string.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    public function withValue(string $value): static
    {
        $length = strlen($value);
        $max = self::MAX_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string where only {$max} bytes are allowed");
        }

        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
