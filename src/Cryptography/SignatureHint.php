<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

final class SignatureHint implements XdrTypedef
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_BYTE_LENGTH = 4;
    protected string $value;

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
        if (!isset($this->value)) {
            throw new InvalidArgumentException('The signature hint is missing a value');
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
        return (new static())->withValue($xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Return the signature hint value.
     *
     * @return string
     */
    public function getValue(): string
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
    public function withValue(string $value): static
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
