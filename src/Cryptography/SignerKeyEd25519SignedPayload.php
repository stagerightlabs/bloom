<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SignerKeyEd25519SignedPayload implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_BYTE_LENGTH = 64;
    protected ED25519 $ed25519;
    protected string $payload;

    /**
     * Create a new signed payload from a signature and a payload.
     *
     * @param ED25519 $ed25519
     * @param string $payload
     * @throws UnexpectedValueException
     * @return static
     */
    public static function of(ED25519 $ed25519, string $payload): static
    {
        return (new static())->withEd25519($ed25519)->withPayload($payload);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ed25519)) {
            throw new InvalidArgumentException('The signed payload is missing a signature');
        }

        if (!isset($this->payload)) {
            throw new InvalidArgumentException('The signed payload is missing a payload');
        }

        $xdr->write($this->ed25519)->write($this->payload, XDR::OPAQUE_VARIABLE);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $signedPayload = new static();
        $signedPayload->ed25519 = $xdr->read(ED25519::class);
        $signedPayload->payload = $xdr->read(XDR::OPAQUE_VARIABLE, length: 64);

        return $signedPayload;
    }

    /**
     * Get the ED25519 signature..
     *
     * @return ED25519
     */
    public function getEd25519(): ED25519
    {
        return $this->ed25519;
    }

    /**
     * Set the ED25519 signature.
     *
     * @param ED25519 $ed25519 signature
     * @return static
     */
    public function withEd25519(ED25519 $ed25519): static
    {
        $clone = Copy::deep($this);
        $clone->ed25519 = Copy::deep($ed25519);

        return $clone;
    }

    /**
     * Get the payload.
     *
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * Accept a payload.
     *
     * @param string $payload
     * @throws InvalidArgumentException
     * @return static
     */
    public function withPayload(string $payload): static
    {
        if (strlen($payload) > self::MAX_BYTE_LENGTH) {
            throw new InvalidArgumentException('Attempting to write a payload that is longer than 64 bytes long.');
        }

        $clone = Copy::deep($this);
        $clone->payload = $payload;

        return $clone;
    }
}
