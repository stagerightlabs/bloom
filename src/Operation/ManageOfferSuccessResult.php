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

final class ManageOfferSuccessResult implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimAtomList $offersClaimed;
    protected ManageOfferSuccessResultOffer $offer;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->offersClaimed)) {
            $this->offersClaimed = ClaimAtomList::empty();
        }

        if (!isset($this->offer)) {
            throw new InvalidArgumentException('The manage offer success result is missing an offer');
        }

        $xdr->write($this->offersClaimed)
            ->write($this->offer);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $manageOfferSuccessResult = new static();
        $manageOfferSuccessResult->offersClaimed = $xdr->read(ClaimAtomList::class);
        $manageOfferSuccessResult->offer = $xdr->read(ManageOfferSuccessResultOffer::class);

        return $manageOfferSuccessResult;
    }

    /**
     * Get the list of offers claimed.
     *
     * @return ClaimAtomList
     */
    public function getOffersClaimed(): ClaimAtomList
    {
        return $this->offersClaimed;
    }

    /**
     * Accept a list of offers claimed.
     *
     * @param ClaimAtomList $offersClaimed
     * @return static
     */
    public function withOffersClaimed(ClaimAtomList $offersClaimed): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->offersClaimed = Copy::deep($offersClaimed);

        return $clone;
    }

    /**
     * Get the offer.
     *
     * @return ManageOfferSuccessResultOffer
     */
    public function getOffer(): ManageOfferSuccessResultOffer
    {
        return $this->offer;
    }

    /**
     * Accept an offer.
     *
     * @param ManageOfferSuccessResultOffer $offer
     * @return static
     */
    public function withOffer(ManageOfferSuccessResultOffer $offer): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->offer = Copy::deep($offer);

        return $clone;
    }
}
