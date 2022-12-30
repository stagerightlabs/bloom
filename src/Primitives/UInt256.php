<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class UInt256 implements XdrTypedef
{
    use NoConstructor;
    use NoChanges;

    /**
     * @var string
     */
    protected $bytes;

    /**
     * Create a new instance of this class from a string.
     *
     * @param string $bytes
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(string $bytes): static
    {
        // Ensure our byte string is no longer than 32 bytes
        if (strlen($bytes) > 32) {
            throw new InvalidArgumentException('Attempting to store more than 32 bytes in a UInt256 is not allowed.');
        }

        // Ensure our byte string is never less than 32 bytes
        $bytes = str_pad($bytes, 32, chr(0), STR_PAD_LEFT);

        return (new static())->withBytes($bytes);
    }

    /**
     * Write this value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $bytes = XDR::pad($this->getBytes(), 32, chr(0), STR_PAD_LEFT);
        $xdr->write($bytes, XDR::OPAQUE_FIXED, 32);
    }

    /**
     * Read this value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return self::of($xdr->read(XDR::OPAQUE_FIXED, length: 32));
    }

    /**
     * Get the value of bytes.
     *
     * @return string
     */
    public function getBytes(): string
    {
        return $this->bytes;
    }

    /**
     * Set the value of bytes.
     *
     * @param string $bytes
     *
     * @return static
     */
    public function withBytes(string $bytes): static
    {
        $clone = Copy::deep($this);
        $clone->bytes = $bytes;

        return $clone;
    }
}
