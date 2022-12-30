<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class BeginSponsoringFutureReservesOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sponsoredId;

    /**
     * Create a begin-sponsoring-future-reserves operation.
     *
     * @param Addressable|string $sponsoredId
     * @param Addressable|string|null $sourceAccount
     * @return Operation
     */
    public static function operation(
        Addressable|string $sponsoredId,
        Addressable|string $sourceAccount = null,
    ): Operation {
        $beginSponsoringFutureReservesOp = (new static())
            ->withSponsoredId($sponsoredId);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::BEGIN_SPONSORING_FUTURE_RESERVES, $beginSponsoringFutureReservesOp))
            ->withSourceAccount($sourceAccount);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->sponsoredId) && $this->sponsoredId instanceof AccountId);
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
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sponsoredId)) {
            throw new InvalidArgumentException('The begin sponsoring future reserves operation is missing a sponsored Id');
        }

        $xdr->write($this->sponsoredId);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $beginSponsoringFutureReservesOp = new static();
        $beginSponsoringFutureReservesOp->sponsoredId = $xdr->read(AccountId::class);

        return $beginSponsoringFutureReservesOp;
    }

    /**
     * Get the sponsored Id.
     *
     * @return AccountId
     */
    public function getSponsoredId(): AccountId
    {
        return $this->sponsoredId;
    }

    /**
     * Accept a sponsored Id.
     *
     * @param AccountId|Addressable|string $sponsoredId
     * @return static
     */
    public function withSponsoredId(AccountId|Addressable|string $sponsoredId): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->sponsoredId = AccountId::fromAddressable($sponsoredId);

        return $clone;
    }
}
