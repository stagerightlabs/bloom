<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerKeyOffer implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sellerId;
    protected Int64 $offerId;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sellerId)) {
            throw new InvalidArgumentException('The ledger key offer is missing a seller Id');
        }

        if (!isset($this->offerId)) {
            throw new InvalidArgumentException('The ledger key offer is missing an offer Id');
        }

        $xdr->write($this->sellerId)->write($this->offerId);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerKeyOffer = new static();
        $ledgerKeyOffer->sellerId = $xdr->read(AccountId::class);
        $ledgerKeyOffer->offerId = $xdr->read(Int64::class);

        return $ledgerKeyOffer;
    }

    /**
     * Get the seller Id.
     *
     * @return AccountId
     */
    public function getSellerId(): AccountId
    {
        return $this->sellerId;
    }

    /**
     * Accept a seller Id.
     *
     * @param AccountId|Addressable|string $sellerId
     * @return static
     */
    public function withSellerId(AccountId|Addressable|string $sellerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->sellerId = AccountId::fromAddressable($sellerId);

        return $clone;
    }

    /**
     * Get the offer Id.
     *
     * @return Int64
     */
    public function getOfferId(): Int64
    {
        return $this->offerId;
    }

    /**
     * Accept an offer Id.
     *
     * @param Int64|int $offerId
     * @return static
     */
    public function withOfferId(Int64|int $offerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->offerId = Int64::normalize($offerId);

        return $clone;
    }
}
