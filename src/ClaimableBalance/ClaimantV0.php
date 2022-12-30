<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClaimantV0 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected ClaimPredicate $predicate;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The claimant v0 is missing an account Id');
        }

        if (!isset($this->predicate)) {
            throw new InvalidArgumentException('The claimant v0 is missing a predicate');
        }

        $xdr->write($this->accountId)->write($this->predicate);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $claimantV0 = new static();
        $claimantV0->accountId = $xdr->read(AccountId::class);
        $claimantV0->predicate = $xdr->read(ClaimPredicate::class);

        return $claimantV0;
    }

    /**
     * Get the account Id.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    /**
     * Accept an account Id.
     *
     * @param AccountId|Addressable|string $accountId
     * @return static
     */
    public function withAccountId(AccountId|Addressable|string $accountId): static
    {
        if (is_string($accountId)) {
            $accountId = AccountId::fromAddressable($accountId);
        } elseif ($accountId instanceof Addressable) {
            $accountId = AccountId::fromAddressable($accountId->getAddress());
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = Copy::deep($accountId);

        return $clone;
    }

    /**
     * Get the claim predicate.
     *
     * @return ClaimPredicate
     */
    public function getPredicate(): ClaimPredicate
    {
        return $this->predicate;
    }

    /**
     * Accept a claim predicate.
     *
     * @param ClaimPredicate $predicate
     * @return static
     */
    public function withPredicate(ClaimPredicate $predicate): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->predicate = Copy::deep($predicate);

        return $clone;
    }
}
