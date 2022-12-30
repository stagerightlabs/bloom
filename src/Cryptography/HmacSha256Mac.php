<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class HmacSha256Mac implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_BYTE_LENGTH = 32;
    protected string $mac;

    /**
     * Create a new instance via a static helper method.
     *
     * @param string $mac
     * @return static
     */
    public static function of(string $mac): static
    {
        return (new static())->withMac($mac);
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
        if (!isset($this->mac)) {
            throw new InvalidArgumentException('The HmacSha256Mac is missing a mac value');
        }

        $xdr->write($this->mac, XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return self::of($xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Get the mac value as a string.
     *
     * @return string
     */
    public function getMac(): string
    {
        return trim($this->mac);
    }

    /**
     * Accept a string as a mac value.
     *
     * @param string $mac
     * @return static
     */
    public function withMac(string $mac): static
    {
        $length = strlen($mac);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string where only {$max} bytes are allowed");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->mac = $mac;

        return $clone;
    }
}
