<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Error implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ErrorCode $code;
    protected String100 $message;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->code)) {
            throw new InvalidArgumentException('The error is missing an error code');
        }

        if (!isset($this->message)) {
            $this->message = String100::of('');
        }

        $xdr->write($this->code)->write($this->message);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $error = new static();
        $error->code = $xdr->read(ErrorCode::class);
        $error->message = $xdr->read(String100::class);

        return $error;
    }

    /**
     * Get the error code.
     *
     * @return ErrorCode
     */
    public function getCode(): ErrorCode
    {
        return $this->code;
    }

    /**
     * Accept an error code.
     *
     * @param ErrorCode $code
     * @return static
     */
    public function withCode(ErrorCode $code): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->code = Copy::deep($code);

        return $clone;
    }

    /**
     * Get the message.
     *
     * @return String100
     */
    public function getMessage(): String100
    {
        return $this->message;
    }

    /**
     * Accept a message.
     *
     * @param String100|string $message
     * @return static
     */
    public function withMessage(String100|string $message): static
    {
        if (is_string($message)) {
            $message = String100::of($message);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->message = Copy::deep($message);

        return $clone;
    }
}
