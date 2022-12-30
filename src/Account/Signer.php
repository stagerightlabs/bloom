<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Signer implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected SignerKey $key;
    protected UInt32 $weight;

    /**
     * Create a new instance via a static helper.
     *
     * @param SignerKey $key
     * @param int $weight
     * @return static
     */
    public static function of(SignerKey $key, UInt32|int $weight = 0): static
    {
        $signer = new static();
        $signer->key = $key;
        $signer->weight = $weight instanceof UInt32 ? $weight : UInt32::of($weight);

        return $signer;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->key)) {
            throw new InvalidArgumentException('The signer does not have a key');
        }

        if (!isset($this->weight)) {
            throw new InvalidArgumentException('The signer does not have a weight');
        }

        $xdr->write($this->key)->write($this->weight);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $signer = new static();
        $signer->key = $xdr->read(SignerKey::class);
        $signer->weight = $xdr->read(UInt32::class);

        return $signer;
    }

    /**
     * Get the signer key.
     *
     * @return SignerKey
     */
    public function getSignerKey(): SignerKey
    {
        return $this->key;
    }

    /**
     * Accept a signer key.
     *
     * @param SignerKey $key
     * @return static
     */
    public function withSignerKey(SignerKey $key): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->key = Copy::deep($key);

        return $clone;
    }

    /**
     * Get the weight.
     *
     * @return UInt32
     */
    public function getWeight(): UInt32
    {
        return $this->weight;
    }

    /**
     * Accept a weight.
     *
     * @param UInt32|int $weight
     * @return static
     */
    public function withWeight(UInt32|int $weight): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->weight = $weight instanceof UInt32
            ? Copy::deep($weight)
            : UInt32::of($weight);

        return $clone;
    }
}
