<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\OptionalAccountId;
use StageRightLabs\Bloom\Account\SignerList;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected Int64 $balance;
    protected SequenceNumber $seqNum;
    protected UInt32 $numSubEntries;
    protected OptionalAccountId $inflationDest;
    protected UInt32 $flags;
    protected String32 $homeDomain;
    protected Thresholds $thresholds;
    protected SignerList $signers;
    protected AccountEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The account entry is missing an account Id');
        }

        if (!isset($this->balance)) {
            throw new InvalidArgumentException('The account entry is missing a balance');
        }

        if (!isset($this->seqNum)) {
            throw new InvalidArgumentException('The account entry is missing a sequence number');
        }

        if (!isset($this->numSubEntries)) {
            throw new InvalidArgumentException('The account entry is missing the number of sub-entries');
        }

        if (!isset($this->inflationDest)) {
            $this->inflationDest = OptionalAccountId::none();
        }

        if (!isset($this->flags)) {
            throw new InvalidArgumentException('The account entry is missing flags');
        }

        if (!isset($this->homeDomain)) {
            throw new InvalidArgumentException('The account entry is missing a home domain');
        }

        if (!isset($this->thresholds)) {
            throw new InvalidArgumentException('The account entry is missing thresholds');
        }

        if (!isset($this->signers)) {
            throw new InvalidArgumentException('The account entry is missing a signer list');
        }

        if (!isset($this->ext)) {
            $this->ext = AccountEntryExt::empty();
        }

        $xdr->write($this->accountId)
            ->write($this->balance)
            ->write($this->seqNum)
            ->write($this->numSubEntries)
            ->write($this->inflationDest)
            ->write($this->flags)
            ->write($this->homeDomain)
            ->write($this->thresholds)
            ->write($this->signers)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $accountEntry = new static();
        $accountEntry->accountId = $xdr->read(AccountId::class);
        $accountEntry->balance = $xdr->read(Int64::class);
        $accountEntry->seqNum = $xdr->read(SequenceNumber::class);
        $accountEntry->numSubEntries = $xdr->read(UInt32::class);
        $accountEntry->inflationDest = $xdr->read(OptionalAccountId::class);
        $accountEntry->flags = $xdr->read(UInt32::class);
        $accountEntry->homeDomain = $xdr->read(String32::class);
        $accountEntry->thresholds = $xdr->read(Thresholds::class);
        $accountEntry->signers = $xdr->read(SignerList::class);
        $accountEntry->ext = $xdr->read(AccountEntryExt::class);

        return $accountEntry;
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
        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = AccountId::fromAddressable($accountId);

        return $clone;
    }

    /**
     * Get the balance.
     *
     * @return Int64
     */
    public function getBalance(): Int64
    {
        return $this->balance;
    }

    /**
     * Accept a balance.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $balance
     * @return static
     */
    public function withBalance(Int64|ScaledAmount|int|string $balance): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->balance = Int64::normalize($balance);

        return $clone;
    }

    /**
     * Get the sequence number.
     *
     * @return SequenceNumber
     */
    public function getSequenceNumber(): SequenceNumber
    {
        return $this->seqNum;
    }

    /**
     * Accept a sequence number.
     *
     * @param SequenceNumber $seqNum
     * @return static
     */
    public function withSequenceNumber(SequenceNumber $seqNum): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->seqNum = Copy::deep($seqNum);

        return $clone;
    }

    /**
     * Get the number of sub entries.
     *
     * @return UInt32
     */
    public function getNumSubEntries(): UInt32
    {
        return $this->numSubEntries;
    }

    /**
     * Accept a number of sub-entries.
     *
     * @param UInt32 $numSubEntries
     * @return static
     */
    public function withNumSubEntries(UInt32 $numSubEntries): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->numSubEntries = Copy::deep($numSubEntries);

        return $clone;
    }

    /**
     * Get the inflation destination.
     *
     * @return AccountId|null
     */
    public function getInflationDestination(): ?AccountId
    {
        return $this->inflationDest->unwrap();
    }

    /**
     * Accept an inflation destination.
     *
     * @param AccountId $inflationDest
     * @return static
     */
    public function withInflationDestination(AccountId $inflationDest): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->inflationDest = OptionalAccountId::some($inflationDest);

        return $clone;
    }

    /**
     * Get the flags.
     *
     * @return UInt32
     */
    public function getFlags(): UInt32
    {
        return $this->flags;
    }

    /**
     * Accept a set of flags.
     *
     * @param UInt32 $flags
     * @return static
     */
    public function withFlags(UInt32 $flags): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->flags = Copy::deep($flags);

        return $clone;
    }

    /**
     * Get the home domain.
     *
     * @return String32
     */
    public function getHomeDomain(): String32
    {
        return $this->homeDomain;
    }

    /**
     * Accept a home domain.
     *
     * @param String32|string $homeDomain
     * @return static
     */
    public function withHomeDomain(String32|string $homeDomain): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->homeDomain = is_string($homeDomain)
            ? String32::of($homeDomain)
            : Copy::deep($homeDomain);

        return $clone;
    }

    /**
     * Get the thresholds.
     *
     * @return Thresholds
     */
    public function getThresholds(): Thresholds
    {
        return $this->thresholds;
    }

    /**
     * Accept a set of thresholds.
     *
     * @param Thresholds $thresholds
     * @return static
     */
    public function withThresholds(Thresholds $thresholds): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->thresholds = Copy::deep($thresholds);

        return $clone;
    }

    /**
     * Get the list of signers.
     *
     * @return SignerList
     */
    public function getSigners(): SignerList
    {
        return $this->signers;
    }

    /**
     * Accept a list of signers.
     *
     * @param SignerList $signers
     * @return static
     */
    public function withSigners(SignerList $signers): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->signers = Copy::deep($signers);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return AccountEntryExt
     */
    public function getExtension(): AccountEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param AccountEntryExt $ext
     * @return static
     */
    public function withExtension(AccountEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
