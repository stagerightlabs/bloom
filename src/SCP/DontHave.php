<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class DontHave implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected MessageType $type;
    protected UInt256 $reqHash;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->type)) {
            $this->type = MessageType::dontHave();
        }

        if (!isset($this->reqHash)) {
            throw new InvalidArgumentException('The DontHave message is missing a request hash');
        }

        $xdr->write($this->type)->write($this->reqHash);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $dontHave = new static();
        $dontHave->type = $xdr->read(MessageType::class);
        $dontHave->reqHash = $xdr->read(UInt256::class);

        return $dontHave;
    }

    /**
     * Get the message type.
     *
     * @return MessageType
     */
    public function getType(): MessageType
    {
        return $this->type;
    }

    /**
     * Accept a message type.
     *
     * @param MessageType $type
     * @return static
     */
    public function withType(MessageType $type): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->type = Copy::deep($type);

        return $clone;
    }

    /**
     * Get the request hash.
     *
     * @return UInt256
     */
    public function getReqHash(): UInt256
    {
        return $this->reqHash;
    }

    /**
     * Accept a request hash.
     *
     * @param UInt256 $reqHash
     * @return static
     */
    public function withReqHash(UInt256 $reqHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->reqHash = Copy::deep($reqHash);

        return $clone;
    }
}
