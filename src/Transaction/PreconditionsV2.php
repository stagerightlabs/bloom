<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use DateTime;
use StageRightLabs\Bloom\Cryptography\ExtraSigners;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class PreconditionsV2 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected OptionalTimeBounds $optionalTimeBounds;
    protected OptionalLedgerBounds $optionalLedgerBounds;
    protected OptionalSequenceNumber $optionalMinimumSequenceNumber;
    protected Duration $minimumSequenceAge;
    protected UInt32 $minimumSequenceLedgerGap;
    protected ExtraSigners $extraSigners;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->optionalTimeBounds)) {
            $this->optionalTimeBounds = OptionalTimeBounds::none();
        }

        if (!isset($this->optionalLedgerBounds)) {
            $this->optionalLedgerBounds = OptionalLedgerBounds::none();
        }

        if (!isset($this->optionalMinimumSequenceNumber)) {
            $this->optionalMinimumSequenceNumber = OptionalSequenceNumber::none();
        }

        $xdr->write($this->optionalTimeBounds)
            ->write($this->optionalLedgerBounds)
            ->write($this->optionalMinimumSequenceNumber)
            ->write($this->getMinimumSequenceAge())
            ->write($this->getMinimumSequenceLedgerGap())
            ->write($this->getExtraSigners());
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $preconditions = new static();
        $preconditions->optionalTimeBounds = $xdr->read(OptionalTimeBounds::class);
        $preconditions->optionalLedgerBounds = $xdr->read(OptionalLedgerBounds::class);
        $preconditions->optionalMinimumSequenceNumber = $xdr->read(OptionalSequenceNumber::class);
        $preconditions->minimumSequenceAge = $xdr->read(Duration::class);
        $preconditions->minimumSequenceLedgerGap = $xdr->read(UInt32::class);
        $preconditions->extraSigners = $xdr->read(ExtraSigners::class);

        return $preconditions;
    }

    /**
     * Return a default PreconditionsV2 instance.
     *
     * @return static
     */
    public static function default(): static
    {
        $preconditions = new static();
        $preconditions->optionalTimeBounds =
            OptionalTimeBounds::some(TimeBounds::oneHour());
        $preconditions->optionalLedgerBounds =
            OptionalLedgerBounds::none();

        return $preconditions;
    }

    /**
     * Get the optional time bounds.
     *
     * @return TimeBounds|null
     */
    public function getTimeBounds(): ?TimeBounds
    {
        if (!isset($this->optionalTimeBounds)) {
            $this->optionalTimeBounds = OptionalTimeBounds::none();
        }

        return $this->optionalTimeBounds->hasValue()
            ? $this->optionalTimeBounds->unwrap()
            : null;
    }

    /**
     * Accept an optional time bounds. This defines the window of validity
     * for a transaction.
     *
     * @param TimeBounds|OptionalTimeBounds $optionalTimeBounds
     * @return static
     */
    public function withTimeBounds(TimeBounds|OptionalTimeBounds $optionalTimeBounds): static
    {
        if ($optionalTimeBounds instanceof TimeBounds) {
            $optionalTimeBounds = OptionalTimeBounds::some($optionalTimeBounds);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalTimeBounds = Copy::deep($optionalTimeBounds);

        return $clone;
    }

    /**
     * Accept a minimum time point as a precondition. A transaction will
     * not be valid until this time and date.
     *
     * @param TimePoint|DateTime|string|int $minTime
     * @return static
     */
    public function withMinimumTimePoint(TimePoint|DateTime|string|int $minTime): static
    {
        $timeBounds = $this->getTimeBounds();

        $timeBounds = $timeBounds
            ? $timeBounds->withMinTime($minTime)
            : (new TimeBounds())->withMinTime($minTime);

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalTimeBounds = OptionalTimeBounds::some($timeBounds);

        return $clone;
    }

    /**
     * Accept a maximum time point as a precondition. A transaction will
     * only be valid until this time and date; then it expires.
     *
     * An integer value will be interpreted as a Unix epoch.
     *
     * @param TimePoint|DateTime|string|int $maxTime
     * @return static
     */
    public function withMaximumTimePoint(TimePoint|DateTime|string|int $maxTime): static
    {
        $timeBounds = $this->getTimeBounds();

        $timeBounds = $timeBounds
            ? $timeBounds->withMaxTime($maxTime)
            : (new TimeBounds())->withMaxTime($maxTime);

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalTimeBounds = OptionalTimeBounds::some($timeBounds);

        return $clone;
    }

    /**
     * Get the optional ledger bounds. This defines how many ledgers
     * must close before the validity of a transaction changes.
     *
     * @return LedgerBounds|null
     */
    public function getLedgerBounds(): ?LedgerBounds
    {
        if (!isset($this->optionalLedgerBounds)) {
            $this->optionalLedgerBounds = OptionalLedgerBounds::none();
        }

        return $this->optionalLedgerBounds->hasValue()
            ? $this->optionalLedgerBounds->unwrap()
            : null;
    }

    /**
     * Accept an optional ledger bounds. This defines how many ledgers
     * must close before the validity of a transaction changes.
     *
     * @param LedgerBounds|OptionalLedgerBounds $optionalLedgerBounds
     * @return static
     */
    public function withLedgerBounds(LedgerBounds|OptionalLedgerBounds $optionalLedgerBounds): static
    {
        if ($optionalLedgerBounds instanceof LedgerBounds) {
            $optionalLedgerBounds = OptionalLedgerBounds::some($optionalLedgerBounds);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalLedgerBounds = Copy::deep($optionalLedgerBounds);

        return $clone;
    }

    /**
     * Accept a minimum ledger offset as a precondition. A transaction
     * will only be valid after this many ledgers have closed.
     *
     * @param UInt32|int $minLedgerOffset
     * @return static
     */
    public function withMinimumLedgerOffset(UInt32|int $minLedgerOffset): static
    {
        $ledgerBounds = $this->getLedgerBounds();

        $ledgerBounds = $ledgerBounds
            ? $ledgerBounds->withMinLedgerOffset($minLedgerOffset)
            : (new LedgerBounds())->withMinLedgerOffset($minLedgerOffset);

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalLedgerBounds = OptionalLedgerBounds::some($ledgerBounds);

        return $clone;
    }

    /**
     * Accept a maximum ledger offset as a precondition. A transaction will
     * only be valid until this many ledgers have closed. If this is set
     * to zero only the minimum offset value will be considered.
     *
     * @param UInt32|int $maxLedgerOffset
     * @return static
     */
    public function withMaximumLedgerOffset(UInt32|int $maxLedgerOffset): static
    {
        $ledgerBounds = $this->getLedgerBounds();

        $ledgerBounds = $ledgerBounds
            ? $ledgerBounds->withMaxLedgerOffset($maxLedgerOffset)
            : (new LedgerBounds())->withMaxLedgerOffset($maxLedgerOffset);

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalLedgerBounds = OptionalLedgerBounds::some($ledgerBounds);

        return $clone;
    }


    /**
     * Get the optional sequence number. Defines what sequence number the
     * source account must reach before the transaction becomes valid.
     *
     * @return SequenceNumber|null
     */
    public function getMinimumSequenceNumber(): ?SequenceNumber
    {
        if (!isset($this->optionalMinimumSequenceNumber)) {
            $this->optionalMinimumSequenceNumber = OptionalSequenceNumber::none();
        }

        return $this->optionalMinimumSequenceNumber->hasValue()
            ? $this->optionalMinimumSequenceNumber->unwrap()
            : null;
    }

    /**
     * Accept an optional sequence number. Defines what sequence number the
     * source account must reach before the transaction becomes valid.
     *
     * @param SequenceNumber|OptionalSequenceNumber $minimumSequenceNumber
     * @return static
     */
    public function withMinimumSequenceNumber(SequenceNumber|OptionalSequenceNumber $minimumSequenceNumber): static
    {
        if ($minimumSequenceNumber instanceof SequenceNumber) {
            $minimumSequenceNumber = OptionalSequenceNumber::some($minimumSequenceNumber);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->optionalMinimumSequenceNumber = Copy::deep($minimumSequenceNumber);

        return $clone;
    }

    /**
     * Get the minimum sequence age duration. This defines the how much older
     * the current ledger's sequence time must be than the source account's
     * sequence time for a transaction to be valid.
     *
     * @return Duration
     */
    public function getMinimumSequenceAge(): Duration
    {
        if (!isset($this->minimumSequenceAge)) {
            $this->minimumSequenceAge = Duration::of(0);
        }

        return $this->minimumSequenceAge;
    }

    /**
     * Accept a minimum sequence age duration. This defines the how much older
     * the current ledger's sequence time must be than the source account's
     * sequence time for a transaction to be valid.
     *
     * @param Duration $minimumSequenceAge
     * @return static
     */
    public function withMinimumSequenceAge(Duration $minimumSequenceAge): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->minimumSequenceAge = Copy::deep($minimumSequenceAge);

        return $clone;
    }

    /**
     * Get the minimum sequence ledger gap. This defines how much greater
     * the current ledger number must be than the source account's
     * sequence ledger number for a transaction to be valid.
     *
     * @return UInt32
     */
    public function getMinimumSequenceLedgerGap(): UInt32
    {
        if (!isset($this->minimumSequenceLedgerGap)) {
            $this->minimumSequenceLedgerGap = UInt32::of(0);
        }

        return $this->minimumSequenceLedgerGap;
    }

    /**
     * Accept a minimum sequence ledger gap. This defines how much greater
     * the current ledger number must be than the source account's
     * sequence ledger number for a transaction to be valid.
     *
     * @param UInt32|int $minimumSequenceLedgerGap
     * @return static
     */
    public function withMinimumSequenceLedgerGap(UInt32|int $minimumSequenceLedgerGap): static
    {
        if (is_int($minimumSequenceLedgerGap)) {
            $minimumSequenceLedgerGap = UInt32::of($minimumSequenceLedgerGap);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->minimumSequenceLedgerGap = Copy::deep($minimumSequenceLedgerGap);

        return $clone;
    }

    /**
     * Get the extra signers array.
     *
     * @return ExtraSigners
     */
    public function getExtraSigners(): ExtraSigners
    {
        if (!isset($this->extraSigners)) {
            $this->extraSigners = ExtraSigners::empty();
        }

        return $this->extraSigners;
    }

    /**
     * Accept an array of extra signers..
     *
     * @param ExtraSigners $extraSigners
     * @return static
     */
    public function withExtraSigners(ExtraSigners $extraSigners): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->extraSigners = Copy::deep($extraSigners);

        return $clone;
    }
}
