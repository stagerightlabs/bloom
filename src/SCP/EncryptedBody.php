<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class EncryptedBody implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const MAX_BYTE_LENGTH = 64000;
    public string $value;

    /**
     * Create a new instance via static helper.
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
            throw new InvalidArgumentException('The encrypted body is missing a value');
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
        $encryptedBody = new static();
        $encryptedBody->value = $xdr->read(XDR::OPAQUE_VARIABLE);

        return $encryptedBody;
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
        $length = strlen($value);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for an encrypted body where only {$max} bytes are allowed");
        }

        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }

    /**
     * Return the underlying value.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->getValue();
    }
}
