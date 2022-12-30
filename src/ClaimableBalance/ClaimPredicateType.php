<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimPredicateType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAIM_PREDICATE_UNCONDITIONAL = 'claimPredicateUnconditional';
    public const CLAIM_PREDICATE_AND = 'claimPredicateAnd';
    public const CLAIM_PREDICATE_OR = 'claimPredicateOr';
    public const CLAIM_PREDICATE_NOT = 'claimPredicateNot';
    public const CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME = 'claimPredicateBeforeAbsoluteTime';
    public const CLAIM_PREDICATE_BEFORE_RELATIVE_TIME = 'claimPredicateBeforeRelativeTime';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::CLAIM_PREDICATE_UNCONDITIONAL,
            1 => self::CLAIM_PREDICATE_AND,
            2 => self::CLAIM_PREDICATE_OR,
            3 => self::CLAIM_PREDICATE_NOT,
            4 => self::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME,
            5 => self::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME,
        ];
    }

    /**
     * Return the selected claim predicate type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_UNCONDITIONAL.
     *
     * @return static
     */
    public static function unconditional(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_UNCONDITIONAL);
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_AND.
     *
     * @return static
     */
    public static function and(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_AND);
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_OR.
     *
     * @return static
     */
    public static function or(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_OR);
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_NOT.
     *
     * @return static
     */
    public static function not(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_NOT);
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME.
     *
     * @return static
     */
    public static function beforeAbsoluteTime(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME);
    }

    /**
     * Create a new instance pre-selected as CLAIM_PREDICATE_BEFORE_RELATIVE_TIME.
     *
     * @return static
     */
    public static function beforeRelativeTime(): static
    {
        return (new static())->withValue(self::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME);
    }
}
