<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class InflationPayout implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $destination;
    protected Int64 $amount;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->destination)) {
            throw new InvalidArgumentException('The inflation payout is missing a destination');
        }

        if (!isset($this->amount)) {
            throw new InvalidArgumentException('The inflation payout is missing an amount');
        }

        $xdr->write($this->destination)
            ->write($this->amount);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $inflationPayout = new static();
        $inflationPayout->destination = $xdr->read(AccountId::class);
        $inflationPayout->amount = $xdr->read(Int64::class);

        return $inflationPayout;
    }

    /**
     * Get the destination account.
     *
     * @return AccountId
     */
    public function getDestination(): AccountId
    {
        return $this->destination;
    }

    /**
     * Accept a destination address.
     *
     * @param AccountId|Addressable|string $destination
     * @return static
     */
    public function withDestination(AccountId|Addressable|string $destination): static
    {
        // addressable
        if ($destination instanceof Addressable) {
            $destination = AccountId::fromAddressable($destination->getAddress());
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
     * Get the amount.
     *
     * @return Int64
     */
    public function getAmount(): Int64
    {
        return $this->amount;
    }

    /**
     * Accept an amount.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $amount
     * @return static
     */
    public function withAmount(Int64|ScaledAmount|int|string $amount): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->amount = Int64::normalize($amount);

        return $clone;
    }
}
