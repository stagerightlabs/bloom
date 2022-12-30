<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

final class Signature implements XdrTypedef
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_BYTE_LENGTH = 64;
    protected string $value;

    /**
     * Create a new instance from a string.
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
        if (!isset($this->value)) {
            throw new InvalidArgumentException('The signature is missing a value');
        }

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
        return (new static())->withRaw($xdr->read(XDR::OPAQUE_VARIABLE));
    }

    /**
     * Return the signature as a string of raw bytes.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->value;
    }

    /**
     * Set the value of the signature.
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
            throw new InvalidArgumentException("Attempting to use a {$length} byte string where only {$max} bytes are allowed");
        }

        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
