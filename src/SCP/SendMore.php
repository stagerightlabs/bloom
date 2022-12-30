<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SendMore implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $numMessages;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->numMessages)) {
            throw new InvalidArgumentException('SendMore is missing a message count');
        }

        $xdr->write($this->numMessages);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $sendMore = new static();
        $sendMore->numMessages = $xdr->read(UInt32::class);

        return $sendMore;
    }

    /**
     * Get the message count.
     *
     * @return UInt32
     */
    public function getNumMessages(): UInt32
    {
        return $this->numMessages;
    }

    /**
     * Accept a message count.
     *
     * @param UInt32|int $numMessages
     * @return static
     */
    public function withNumMessages(UInt32|int $numMessages): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->numMessages = is_int($numMessages)
            ? UInt32::of($numMessages)
            : Copy::deep($numMessages);

        return $clone;
    }
}
