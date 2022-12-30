<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use DateTime;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ClaimPredicate extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClaimPredicateType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClaimPredicateType::CLAIM_PREDICATE_UNCONDITIONAL        => XDR::VOID,
            ClaimPredicateType::CLAIM_PREDICATE_AND                  => ClaimPredicateList::class,
            ClaimPredicateType::CLAIM_PREDICATE_OR                   => ClaimPredicateList::class,
            ClaimPredicateType::CLAIM_PREDICATE_NOT                  => OptionalClaimPredicate::class,
            ClaimPredicateType::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME => Int64::class,
            ClaimPredicateType::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME => Int64::class,
        ];
    }

    /**
     * Create a new unconditional instance.
     *
     * @return static
     */
    public static function unconditional(): static
    {
        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::unconditional();
        $claimPredicate->value = null;

        return $claimPredicate;
    }

    /**
     * Create a new 'AND' instance that wraps a claim predicate list.
     *
     * @param ClaimPredicateList|array<ClaimPredicate> $claimPredicateList
     * @return static
     */
    public static function and(ClaimPredicateList|array $claimPredicateList): static
    {
        if (is_array($claimPredicateList)) {
            $claimPredicateList = ClaimPredicateList::normalize($claimPredicateList);
        }

        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::and();
        $claimPredicate->value = $claimPredicateList;

        return $claimPredicate;
    }

    /**
     * Create a new 'OR' instance that wraps a claim predicate list.
     *
     * @param ClaimPredicateList|array<ClaimPredicate> $claimPredicateList
     * @return static
     */
    public static function or(ClaimPredicateList|array $claimPredicateList): static
    {
        if (is_array($claimPredicateList)) {
            $claimPredicateList = ClaimPredicateList::normalize($claimPredicateList);
        }

        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::or();
        $claimPredicate->value = $claimPredicateList;

        return $claimPredicate;
    }

    /**
     * Create a new 'NOT' instance that wraps an optional claim predicate.
     *
     * @param ClaimPredicate|OptionalClaimPredicate $opposingClaimPredicate
     * @return static
     */
    public static function not(ClaimPredicate|OptionalClaimPredicate $opposingClaimPredicate): static
    {
        if ($opposingClaimPredicate instanceof ClaimPredicate) {
            $opposingClaimPredicate = OptionalClaimPredicate::some($opposingClaimPredicate);
        }

        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::not();
        $claimPredicate->value = $opposingClaimPredicate;

        return $claimPredicate;
    }

    /**
     * Create a new 'before absolute time' instance that wraps a timestamp.
     * The predicate will be true if ledger close time is less than
     * the absolute time provided as a unix epoch timestamp.
     *
     * @param DateTime|TimePoint|Int64|int $epoch
     * @return static
     */
    public static function beforeAbsoluteTime(DateTime|TimePoint|Int64|int $epoch): static
    {
        if ($epoch instanceof DateTime) {
            $epoch = TimePoint::fromDateTime($epoch);
        }

        if ($epoch instanceof TimePoint) {
            $epoch = $epoch->toUnixEpoch();
        }

        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::beforeAbsoluteTime();
        $claimPredicate->value = Int64::normalize($epoch);

        return $claimPredicate;
    }

    /**
     * Create a new 'before relative time' instance that wraps an integer.
     * The predicate will be true for this many seconds after the close
     * of the ledger in which this claimable balance entry was created.
     *
     * @param Int64|int $seconds
     * @return static
     */
    public static function beforeRelativeTime(Int64|int $seconds): static
    {
        $claimPredicate = new static();
        $claimPredicate->discriminator = ClaimPredicateType::beforeRelativeTime();
        $claimPredicate->value = Int64::normalize($seconds);

        return $claimPredicate;
    }

    /**
     * Return the underlying value.
     *
     * @return ClaimPredicateList|OptionalClaimPredicate|Int64|null
     */
    public function unwrap(): ClaimPredicateList|OptionalClaimPredicate|Int64|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
