<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Cryptography\ExtraSigners;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class Preconditions extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return PreconditionType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            PreconditionType::PRECONDITION_NONE => XDR::VOID,
            PreconditionType::PRECONDITION_TIME => TimeBounds::class,
            PreconditionType::PRECONDITION_V2   => PreconditionsV2::class,
        ];
    }

    /**
     * Create a default set of preconditions.
     *
     * @return static
     */
    public static function default(): static
    {
        return (new static())->wrapPreconditionsV2(PreconditionsV2::default());
    }


    /**
     * Create an empty set of preconditions.
     *
     * @return static
     */
    public static function none(): static
    {
        $preconditions = new static();
        $preconditions->discriminator = PreconditionType::none();
        $preconditions->value = XDR::VOID;

        return $preconditions;
    }

    /**
     * Create a set of preconditions.
     *
     * @param PreconditionsV2|null $preconditionsV2
     * @return static
     */
    public static function wrapPreconditionsV2(PreconditionsV2 $preconditionsV2 = null): static
    {
        if (is_null($preconditionsV2)) {
            $preconditionsV2 = new PreconditionsV2();
        }

        $preconditions = new static();
        $preconditions->discriminator = PreconditionType::v2();
        $preconditions->value = $preconditionsV2;

        return $preconditions;
    }

    /**
     * Create a Preconditions instance representing time bounds.
     *
     * @param TimeBounds|OptionalTimeBounds $timeBounds
     * @return static
     */
    public static function wrapTimeBounds(TimeBounds|OptionalTimeBounds $timeBounds): static
    {
        if ($timeBounds instanceof OptionalTimeBounds && is_null($timeBounds->unwrap())) {
            return self::none();
        }

        if ($timeBounds instanceof OptionalTimeBounds) {
            $timeBounds = $timeBounds->unwrap();
        }

        $preconditions = new static();
        $preconditions->discriminator = PreconditionType::time();
        $preconditions->value = $timeBounds;

        return $preconditions;
    }

    /**
     * Return the underlying preconditions.
     *
     * @return PreconditionsV2|TimeBounds|string|null
     */
    public function unwrap(): PreconditionsV2|TimeBounds|string|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the time bounds, if present.
     *
     * @return TimeBounds|null
     */
    public function getTimeBounds(): ?TimeBounds
    {
        if ($this->unwrap() instanceof TimeBounds) {
            return $this->unwrap();
        }

        if ($this->unwrap() instanceof PreconditionsV2 && $this->unwrap()->getTimeBounds()) {
            return $this->unwrap()->getTimeBounds();
        }

        return null;
    }

    /**
     * Return the ledger bounds, if present.
     *
     * @return LedgerBounds|null
     */
    public function getLedgerBounds(): ?LedgerBounds
    {
        if ($this->unwrap() instanceof PreconditionsV2 && $this->unwrap()->getLedgerBounds()) {
            return $this->unwrap()->getLedgerBounds();
        }

        return null;
    }

    /**
     * Return the minimum source account sequence number, if present.
     *
     * @return SequenceNumber|null
     */
    public function getMinimumSequenceNumber(): ?SequenceNumber
    {
        if ($this->unwrap() instanceof PreconditionsV2 && $this->unwrap()->getMinimumSequenceNumber()) {
            return $this->unwrap()->getMinimumSequenceNumber();
        }

        return null;
    }

    /**
     * Return the minimum sequence age duration, if present.
     *
     * @return Duration|null
     */
    public function getMinimumSequenceAge(): ?Duration
    {
        if ($this->unwrap() instanceof PreconditionsV2) {
            return $this->unwrap()->getMinimumSequenceAge();
        }

        return null;
    }

    /**
     * Return the minimum sequence ledger gap, if present.
     *
     * @return UInt32|null
     */
    public function getMinimumSequenceLedgerGap(): ?UInt32
    {
        if ($this->unwrap() instanceof PreconditionsV2) {
            return $this->unwrap()->getMinimumSequenceLedgerGap();
        }

        return null;
    }

    /**
     * Return the extra signers, if present.
     *
     * @return ExtraSigners|null
     */
    public function getExtraSigners(): ?ExtraSigners
    {
        if ($this->unwrap() instanceof PreconditionsV2) {
            return $this->unwrap()->getExtraSigners();
        }

        return null;
    }
}
