<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class AccountMergeResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ACCOUNT_MERGE_SUCCESS = 'accountMergeSuccess';
    public const ACCOUNT_MERGE_MALFORMED = 'accountMergeMalformed'; // can't merge onto itself
    public const ACCOUNT_MERGE_NO_ACCOUNT = 'accountMergeNoAccount'; // destination does not exist
    public const ACCOUNT_MERGE_IMMUTABLE_SET = 'accountMergeImmutableSet'; // source account has AUTH_IMMUTABLE set
    public const ACCOUNT_MERGE_HAS_SUB_ENTRIES = 'accountMergeHasSubEntries'; // account has trust lines/offers
    public const ACCOUNT_MERGE_SEQNUM_TOO_FAR = 'accountMergeSeqnumTooFar'; // sequence number is over max allowed
    public const ACCOUNT_MERGE_DEST_FULL = 'accountMergeDestFull'; // can't add source balance to destination balance
    public const ACCOUNT_MERGE_IS_SPONSOR = 'accountMergeIsSponsor'; // can't merge an account that is sponsoring

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::ACCOUNT_MERGE_SUCCESS,
            -1 => self::ACCOUNT_MERGE_MALFORMED,
            -2 => self::ACCOUNT_MERGE_NO_ACCOUNT,
            -3 => self::ACCOUNT_MERGE_IMMUTABLE_SET,
            -4 => self::ACCOUNT_MERGE_HAS_SUB_ENTRIES,
            -5 => self::ACCOUNT_MERGE_SEQNUM_TOO_FAR,
            -6 => self::ACCOUNT_MERGE_DEST_FULL,
            -7 => self::ACCOUNT_MERGE_IS_SPONSOR,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_NO_ACCOUNT.
     *
     * @return static
     */
    public static function noAccount(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_NO_ACCOUNT);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_IMMUTABLE_SET.
     *
     * @return static
     */
    public static function immutableSet(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_IMMUTABLE_SET);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_HAS_SUB_ENTRIES.
     *
     * @return static
     */
    public static function hasSubEntries(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_HAS_SUB_ENTRIES);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_SEQNUM_TOO_FAR.
     *
     * @return static
     */
    public static function sequenceNumberTooFar(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_SEQNUM_TOO_FAR);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_DEST_FULL.
     *
     * @return static
     */
    public static function destinationFull(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_DEST_FULL);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE_IS_SPONSOR.
     *
     * @return static
     */
    public static function isSponsor(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE_IS_SPONSOR);
    }
}
