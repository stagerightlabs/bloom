<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Keypair\StringKey;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class MuxedAccountMed25519 implements XdrStruct
{
    /**
     * Properties
     */
    protected UInt64 $id;
    protected ED25519 $ed25519;

    /**
     * Write this struct to XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->id)) {
            throw new InvalidArgumentException('The muxed account is missing an id');
        }

        if (!isset($this->ed25519)) {
            throw new InvalidArgumentException('The muxed account is missing an ED25519 byte string');
        }

        $xdr->write($this->id, UInt64::class)
            ->write($this->ed25519, ED25519::class);
    }

    /**
     * Read the operation from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $instance = new static();
        $instance->id = $xdr->read(UInt64::class);
        $instance->ed25519 = $xdr->read(ED25519::class);

        return $instance;
    }

    /**
     * Get the id.
     *
     * @return UInt64
     */
    public function getId(): UInt64
    {
        return $this->id;
    }

    /**
     * Set the id.
     *
     * @param UInt64 $id
     *
     * @return self
     */
    public function withId(UInt64 $id): self
    {
        $clone = Copy::deep($this);
        $clone->id = $id;

        return $clone;
    }

    /**
     * Get the ED25519 key.
     *
     * @return ED25519
     */
    public function getEd25519(): ED25519
    {
        return $this->ed25519;
    }

    /**
     * Set the ED25519 key.
     *
     * @param ED25519 $ed25519
     *
     * @return self
     */
    public function withEd25519(ED25519 $ed25519): self
    {
        $clone = Copy::deep($this);
        $clone->ed25519 = $ed25519;

        return $clone;
    }

    /**
     * Return the muxed address string.
     *
     * @return string
     */
    public function getAddress(): string
    {
        return StringKey::constructMuxedAddress(
            $this->ed25519->getAddress(),
            $this->id
        );
    }

    /**
     * Construct a new instance from a muxed address string.
     *
     * @param Addressable|string $address
     * @throws InvalidArgumentException
     * @return static
     */
    public static function fromMuxedAddress(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        if (!StringKey::isValidMuxedAddress($address)) {
            throw new InvalidArgumentException('Attempting to use invalid muxed address');
        }

        $deconstructed = StringKey::deconstructMuxedAddress($address);

        $ed25519 = ED25519::of($deconstructed['ed25519']);

        $instance = new static();
        $instance->id = $deconstructed['id'];
        $instance->ed25519 = $ed25519;

        return $instance;
    }
}
