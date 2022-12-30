<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use DateTime;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Service;
use StageRightLabs\Bloom\Transaction\TimePoint;

final class ClaimPredicateService extends Service
{
    /**
     * Create an 'unconditional' claim predicate.
     *
     * @return ClaimPredicate
     */
    public function unconditional(): ClaimPredicate
    {
        return ClaimPredicate::unconditional();
    }

    /**
     * Create an 'and' predicate that requires that all provided predicates pass.
     *
     * @param ClaimPredicateList|array<ClaimPredicate> $claimPredicateList
     * @return ClaimPredicate
     */
    public function and(ClaimPredicateList|array $claimPredicateList): ClaimPredicate
    {
        return ClaimPredicate::and($claimPredicateList);
    }

    /**
     * Create an 'or' predicate that requires at least one of the provided predicates pass.
     *
     * @param ClaimPredicateList|array<ClaimPredicate> $claimPredicateList
     * @return ClaimPredicate
     */
    public function or(ClaimPredicateList|array $claimPredicateList): ClaimPredicate
    {
        return ClaimPredicate::or($claimPredicateList);
    }

    /**
     * Create a 'not' predicate that inverts the provided predicate.
     *
     * @param ClaimPredicate|OptionalClaimPredicate $claimPredicate
     * @return ClaimPredicate
     */
    public function not(ClaimPredicate|OptionalClaimPredicate $claimPredicate): ClaimPredicate
    {
        return ClaimPredicate::not($claimPredicate);
    }

    /**
     * Create a new 'before absolute time' predicate that will be valid if
     * the ledger close time is less than the absolute time provided.
     *
     * @param DateTime|TimePoint|Int64|int $epoch
     * @return ClaimPredicate
     */
    public function beforeAbsoluteTime(DateTime|TimePoint|Int64|int $epoch): ClaimPredicate
    {
        return ClaimPredicate::beforeAbsoluteTime($epoch);
    }

    /**
     * Create a new 'before relative time' predicate that will be valid
     * for the specified number of  seconds after the close of the
     * ledger in which this claimable balance entry was created.
     *
     * @param Int64|int $seconds
     * @return ClaimPredicate
     */
    public function beforeRelativeTime(Int64|int $seconds): ClaimPredicate
    {
        return ClaimPredicate::beforeRelativeTime($seconds);
    }

    /**
     * Create a claim predicate list from an array of claim predicates.
     *
     * @param ClaimPredicate|array<ClaimPredicate> $predicates
     * @return ClaimPredicateList
     */
    public function collect($predicates = []): ClaimPredicateList
    {
        return ClaimPredicateList::normalize($predicates);
    }
}
