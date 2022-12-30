<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AuthCert implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Curve25519Public $pubkey;
    protected UInt64 $expiration;
    protected Signature $sig;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->pubkey)) {
            throw new InvalidArgumentException('The auth cert is missing a public key');
        }

        if (!isset($this->expiration)) {
            throw new InvalidArgumentException('The auth cert is missing an expiration');
        }

        if (!isset($this->sig)) {
            throw new InvalidArgumentException('The auth cert is missing a signature');
        }

        $xdr->write($this->pubkey)->write($this->expiration)->write($this->sig);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $authCert = new static();
        $authCert->pubkey = $xdr->read(Curve25519Public::class);
        $authCert->expiration = $xdr->read(UInt64::class);
        $authCert->sig = $xdr->read(Signature::class);

        return $authCert;
    }

    /**
     * Get the public key.
     *
     * @return Curve25519Public
     */
    public function getPublicKey(): Curve25519Public
    {
        return $this->pubkey;
    }

    /**
     * Accept a public key.
     *
     * @param Curve25519Public $pubkey
     * @return static
     */
    public function withPublicKey(Curve25519Public $pubkey): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->pubkey = Copy::deep($pubkey);

        return $clone;
    }

    /**
     * Get the expiration.
     *
     * @return UInt64
     */
    public function getExpiration(): UInt64
    {
        return $this->expiration;
    }

    /**
     * Accept an expiration.
     *
     * @param UInt64 $expiration
     * @return static
     */
    public function withExpiration(UInt64 $expiration): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->expiration = Copy::deep($expiration);

        return $clone;
    }

    /**
     * Get the signature.
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->sig;
    }

    /**
     * Accept a signature.
     *
     * @param Signature $sig
     * @return static
     */
    public function withSignature(Signature $sig): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sig = Copy::deep($sig);

        return $clone;
    }
}
