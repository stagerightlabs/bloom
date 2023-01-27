<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class PredicateResource extends Resource
{
    /**
     * If true it means this clause of the condition is always satisfied.
     *
     * @return bool
     */
    public function isUnconditional(): bool
    {
        return boolval($this->payload->getBoolean('unconditional'));
    }

    /**
     * If set, the array will always contain two elements which also are
     * predicates. This clause of the condition is satisfied if both
     * of the two elements in the array are satisfied.
     *
     * @return array<PredicateResource>
     */
    public function getAndPredicates(): array
    {
        return array_map(function ($and) {
            return PredicateResource::wrap($and);
        }, $this->payload->getArray('and') ?? []);
    }

    /**
     * If set, the array will always contain two elements which also are
     * predicates. This clause of the condition is satisfied if at
     * least one of the two elements in the array are satisfied.
     *
     * @return array<PredicateResource>
     */
    public function getOrPredicates(): array
    {
        return array_map(function ($or) {
            return PredicateResource::wrap($or);
        }, $this->payload->getArray('or') ?? []);
    }

    /**
     * If set, This clause of the condition is satisfied if the value is
     * not satisfied.
     *
     * @return PredicateResource|null
     */
    public function getNotPredicate(): ?PredicateResource
    {
        if ($notPredicate = $this->payload->getArray('not')) {
            return PredicateResource::wrap($notPredicate);
        }

        return null;
    }

    /**
     * A customized ISO 8601 formatted string representing a deadline for when
     * the claimable balance can be claimed. If the balance is claimed before
     * the date then this clause of the condition is satisfied. The format of
     * this date string is a custom extension on top of ISO 8601 format. It
     * allows for years to be outside the 0000-9999 range. The dates are
     * derived from a unix epoch value in range of signed 64 bit integer.
     *
     * This means the date expresses a much larger calendar  range of
     * 292277026596 years into future and -292471206707 years back in past.
     *
     * This custom extension format will add a '+' prefix on values that go
     * beyond year 9999 into the future and for years that are prior to year
     * 0(B.C per Gregorian calendar) it will add prefix of '-'.
     *
     * For Example:
     * - `2022-02-10T15:30:22Z`
     * - `39121901036-03-29T15:30:22Z`
     * - `7025-12-23T00:00:00Z`
     *
     * @return string|null
     */
    public function getAbsoluteBeforeTimestamp(): ?string
    {
        return $this->payload->getString('abs_before');
    }

    /**
     * A unix epoch value in seconds representing the same deadline date for
     * when the claimable balance can be claimed. It is the same date/time
     * value that absBefore represents, just expressed in integral unix
     * epoch seconds within the range of a signed 64bit integer.
     *
     * @return string|null
     */
    public function getAbsoluteBeforeEpoch(): ?string
    {
        return $this->payload->getString('abs_before_epoch');
    }

    /**
     * A relative deadline for when the claimable balance can be claimed.
     * The value represents the number of seconds since the close time
     * of the ledger which created the claimable balance.
     *
     * @return string|null
     */
    public function getRelativeBeforeSeconds(): ?string
    {
        return $this->payload->getString('rel_before');
    }
}
