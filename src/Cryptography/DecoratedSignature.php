<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Cryptography;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class DecoratedSignature implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected SignatureHint $hint;
    protected Signature $signature;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->hint)) {
            throw new InvalidArgumentException('The decorated signature is missing a hint');
        }

        if (!isset($this->signature)) {
            throw new InvalidArgumentException('The decorated signature is missing a signature');
        }

        $xdr->write($this->hint, SignatureHint::class)
            ->write($this->signature, Signature::class);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $decoratedSignature = new static();
        $decoratedSignature->hint = $xdr->read(SignatureHint::class);
        $decoratedSignature->signature = $xdr->read(Signature::class);

        return $decoratedSignature;
    }

    /**
     * Return the signature hint.
     *
     * @return SignatureHint
     */
    public function getHint(): SignatureHint
    {
        return $this->hint;
    }

    /**
     * Set the signature hint.
     *
     * @param SignatureHint $hint
     * @return static
     */
    public function withHint(SignatureHint $hint): static
    {
        $clone = Copy::deep($this);
        $clone->hint = Copy::deep($hint);

        return $clone;
    }

    /**
     * Return the signature
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->signature;
    }

    /**
     * Set the signature.
     *
     * @param Signature $signature
     * @return static
     */
    public function withSignature(Signature $signature): static
    {
        $clone = Copy::deep($this);
        $clone->signature = Copy::deep($signature);

        return $clone;
    }
}
