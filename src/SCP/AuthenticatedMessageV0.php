<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\HmacSha256Mac;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AuthenticatedMessageV0 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt64 $sequence;
    protected StellarMessage $message;
    protected HmacSha256Mac $mac;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sequence)) {
            throw new InvalidArgumentException('The AuthenticatedMessageV0 is missing a sequence');
        }

        if (!isset($this->message)) {
            throw new InvalidArgumentException('The AuthenticatedMessageV0 is missing a message');
        }

        if (!isset($this->mac)) {
            throw new InvalidArgumentException('The AuthenticatedMessageV0 is missing an HmacSha256Mac');
        }

        $xdr->write($this->sequence)
            ->write($this->message)
            ->write($this->mac);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $authenticatedMessageV0 = new static();
        $authenticatedMessageV0->sequence = $xdr->read(UInt64::class);
        $authenticatedMessageV0->message = $xdr->read(StellarMessage::class);
        $authenticatedMessageV0->mac = $xdr->read(HmacSha256Mac::class);

        return $authenticatedMessageV0;
    }

    /**
     * Get the sequence.
     *
     * @return UInt64
     */
    public function getSequence(): UInt64
    {
        return $this->sequence;
    }

    /**
     * Accept a sequence.
     *
     * @param UInt64 $sequence
     * @return static
     */
    public function withSequence(UInt64 $sequence): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sequence = Copy::deep($sequence);

        return $clone;
    }

    /**
     * Get the message.
     *
     * @return StellarMessage
     */
    public function getMessage(): StellarMessage
    {
        return $this->message;
    }

    /**
     * Accept a message.
     *
     * @param StellarMessage $message
     * @return static
     */
    public function withMessage(StellarMessage $message): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->message = Copy::deep($message);

        return $clone;
    }

    /**
     * Get the mac value.
     *
     * @return HmacSha256Mac
     */
    public function getMac(): HmacSha256Mac
    {
        return $this->mac;
    }

    /**
     * Accept a mac value.
     *
     * @param HmacSha256Mac $mac
     * @return static
     */
    public function withMac(HmacSha256Mac $mac): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->mac = Copy::deep($mac);

        return $clone;
    }
}
