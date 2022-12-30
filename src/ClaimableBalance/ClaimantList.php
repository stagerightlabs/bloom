<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;

/**
 * @extends Arr<Claimant>
 */
class ClaimantList extends Arr implements XdrArray
{
    /**
     * Claimant lists are limited to 10 entries.
     */
    public const MAX_LENGTH = 10;

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    public static function getXdrType(): string
    {
        return Claimant::class;
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
     * Convert an array of account addresses into a claimant list, using only
     * the default 'allow all' predicate.
     *
     * @param ClaimantList|Claimant|array<Claimant|string> $claimants
     * @return static
     */
    public static function normalize(ClaimantList|Claimant|array $claimants): static
    {
        if ($claimants instanceof ClaimantList) {
            /** @var static */
            return Copy::deep($claimants);
        }

        if ($claimants instanceof Claimant) {
            $claimants = [$claimants];
        }

        return array_reduce($claimants, function ($list, $claimant) {
            $claimant = is_string($claimant)
                ? Claimant::fromAddressable($claimant)
                : $claimant;

            return $list->push($claimant);
        }, new static());
    }
}
