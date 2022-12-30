<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\OptionalAddress;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Operation implements XdrStruct
{
    /**
     * Properties
     */
    protected ?OptionalAddress $sourceAccount = null;
    protected OperationBody $body;

    /**
     * Write the struct to XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sourceAccount)) {
            throw new InvalidArgumentException('The operation is missing a source account');
        }

        if (!isset($this->body)) {
            throw new InvalidArgumentException('The operation is missing a body');
        }

        $xdr->write($this->sourceAccount)
            ->write($this->body);
    }

    /**
     * Read the struct from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $operation = new static();
        $operation->sourceAccount = $xdr->read(OptionalAddress::class);
        $operation->body = $xdr->read(OperationBody::class);

        return $operation;
    }

    /**
     * Get the source account, if available.
     *
     * @return AccountId|null
     */
    public function getSourceAccount(): ?AccountId
    {
        return $this->sourceAccount && $this->sourceAccount->hasValue()
            ? $this->sourceAccount->unwrap()
            : null;
    }

    /**
     * Set the source account.
     *
     * @param OptionalAddress|AccountId|Addressable|string|null $sourceAccount
     *
     * @return static
     */
    public function withSourceAccount(OptionalAddress|AccountId|Addressable|string $sourceAccount = null): static
    {
        /** @var static */
        $clone = Copy::deep($this);

        if ($sourceAccount instanceof OptionalAddress) {
            $clone->sourceAccount = Copy::deep($sourceAccount);
        } else {
            $clone->sourceAccount = is_null($sourceAccount)
                ? OptionalAddress::none()
                : OptionalAddress::some($sourceAccount);
        }

        return $clone;
    }

    /**
     * Get the operation body.
     *
     * @return OperationBody
     */
    public function getBody(): OperationBody
    {
        return $this->body;
    }

    /**
     * Set the operation body.
     *
     * @param OperationBody $body
     *
     * @return self
     */
    public function withBody(OperationBody $body): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->body = $body;

        return $clone;
    }
}
