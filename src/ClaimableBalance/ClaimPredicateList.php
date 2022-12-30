<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<ClaimPredicate>
 */
class ClaimPredicateList extends Arr implements XdrArray
{
    /**
     * The claim predicate list is limited to 2 entries at most.
     */
    public const MAX_LENGTH = 2;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return ClaimPredicate::class;
    }

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return self::MAX_LENGTH;
    }

    /**
     * Instantiate an empty array.
     *
     * @return static
     */
    public static function empty(): static
    {
        return static::of([]);
    }

    /**
     * Convert an array of claim predicates into a claim predicate list.
     *
     * @param ClaimPredicateList|ClaimPredicate|array<ClaimPredicate> $predicates
     * @return static
     */
    public static function normalize(ClaimPredicateList|ClaimPredicate|array $predicates = []): static
    {
        if ($predicates instanceof ClaimPredicateList) {
            /** @var static */
            return Copy::deep($predicates);
        }

        if ($predicates instanceof ClaimPredicate) {
            $predicates = [$predicates];
        }

        return array_reduce($predicates, function ($list, $predicate) {
            if (!$predicate instanceof ClaimPredicate) {
                return $list;
            }
            return $list->push($predicate);
        }, new static());
    }
}
