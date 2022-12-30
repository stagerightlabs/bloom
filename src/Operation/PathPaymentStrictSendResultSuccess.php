<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Offer\ClaimAtomList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class PathPaymentStrictSendResultSuccess implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimAtomList $offers;
    protected SimplePaymentResult $last;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->offers)) {
            $this->offers = ClaimAtomList::empty();
        }

        if (!isset($this->last)) {
            throw new InvalidArgumentException('The path payment strict send result success is missing a simple payment result');
        }

        $xdr
            ->write($this->offers)
            ->write($this->last);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $pathPaymentStrictSendResultSuccess = new static();
        $pathPaymentStrictSendResultSuccess->offers = $xdr->read(ClaimAtomList::class);
        $pathPaymentStrictSendResultSuccess->last = $xdr->read(SimplePaymentResult::class);

        return $pathPaymentStrictSendResultSuccess;
    }

    /**
     * Get the list of offers.
     *
     * @return ClaimAtomList
     */
    public function getOffers(): ClaimAtomList
    {
        return $this->offers;
    }

    /**
     * Accept a list of offers.
     *
     * @param ClaimAtomList $offers
     * @return static
     */
    public function withOffers(ClaimAtomList $offers): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->offers = Copy::deep($offers);

        return $clone;
    }

    /**
     * Get the payment result.
     *
     * @return SimplePaymentResult
     */
    public function getPaymentResult(): SimplePaymentResult
    {
        return $this->last;
    }

    /**
     * Accept a payment result.
     *
     * @param SimplePaymentResult $last
     * @return static
     */
    public function withPaymentResult(SimplePaymentResult $last): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->last = Copy::deep($last);

        return $clone;
    }
}
