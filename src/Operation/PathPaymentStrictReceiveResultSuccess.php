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

final class PathPaymentStrictReceiveResultSuccess implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimAtomList $claimAtomList;
    protected SimplePaymentResult $last;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->claimAtomList)) {
            throw new InvalidArgumentException('The path payment strict receive result success is missing a claim atom list.');
        }

        if (!isset($this->last)) {
            throw new InvalidArgumentException('The path payment strict receive result success is missing a simple payment result.');
        }

        $xdr->write($this->claimAtomList)->write($this->last);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $pathPaymentStrictReceiveResultSuccess = new static();
        $pathPaymentStrictReceiveResultSuccess->claimAtomList = $xdr->read(ClaimAtomList::class);
        $pathPaymentStrictReceiveResultSuccess->last = $xdr->read(SimplePaymentResult::class);

        return $pathPaymentStrictReceiveResultSuccess;
    }

    /**
     * Get the claim atom list.
     *
     * @return ClaimAtomList
     */
    public function getClaimAtomList(): ClaimAtomList
    {
        return $this->claimAtomList;
    }

    /**
     * Accept a claim atom list.
     *
     * @param ClaimAtomList $claimAtomList
     * @return static
     */
    public function withClaimAtomList(ClaimAtomList $claimAtomList): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->claimAtomList = Copy::deep($claimAtomList);

        return $clone;
    }

    /**
     * Get the last payment result.
     *
     * @return SimplePaymentResult
     */
    public function getLast(): SimplePaymentResult
    {
        return $this->last;
    }

    /**
     * Accept a last payment result.
     *
     * @param SimplePaymentResult $last
     * @return static
     */
    public function withLast(SimplePaymentResult $last): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->last = Copy::deep($last);

        return $clone;
    }
}
