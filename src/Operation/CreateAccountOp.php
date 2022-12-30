<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class CreateAccountOp implements OperationVariety, XdrStruct
{
    /**
     * Properties
     */
    protected AccountId $destination;
    protected Int64 $startingBalance;

    /**
     * Create a new create-account operation.
     *
     * @param Addressable|string $destination
     * @param Int64|ScaledAmount|integer|string $startingBalance
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        Addressable|string $destination,
        Int64|ScaledAmount|int|string $startingBalance,
        Addressable|string $source = null
    ): Operation {
        $createAccountOp = (new static())
            ->withDestination($destination)
            ->withStartingBalance($startingBalance);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CREATE_ACCOUNT, $createAccountOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->destination) && $this->destination instanceof AccountId)
            && (isset($this->startingBalance) && $this->startingBalance instanceof Int64);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_MEDIUM;
    }

    /**
     * Write this operation to XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->destination)) {
            throw new InvalidArgumentException('The create account op is missing a destination');
        }

        if (!isset($this->startingBalance)) {
            throw new InvalidArgumentException('The create account op is missing a destination');
        }

        $xdr->write($this->destination)->write($this->startingBalance);
    }

    /**
     * Read the operation from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $createAccountOperation = new static();
        $createAccountOperation->destination = $xdr->read(AccountId::class);
        $createAccountOperation->startingBalance = $xdr->read(Int64::class);

        return $createAccountOperation;
    }

    /**
     * Get the destination.
     *
     * @return AccountId
     */
    public function getDestination(): AccountId
    {
        return $this->destination;
    }

    /**
     * Set the destination.
     *
     * @param Addressable|AccountId|string $destination
     *
     * @return self
     */
    public function withDestination(Addressable|AccountId|string $destination): self
    {
        // addressable
        if ($destination instanceof Addressable) {
            $destination = $destination->getAccountId();
        }

        // string
        if (is_string($destination)) {
            $destination = AccountId::fromAddressable($destination);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->destination = Copy::deep($destination);

        return $clone;
    }

    /**
     * Get the starting balance.
     *
     * @return Int64
     */
    public function getStartingBalance(): Int64
    {
        return $this->startingBalance;
    }

    /**
     * Set the value of startingBalance.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $startingBalance
     * @return self
     */
    public function withStartingBalance(Int64|ScaledAmount|int|string $startingBalance): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->startingBalance = Int64::normalize($startingBalance);

        return $clone;
    }
}
