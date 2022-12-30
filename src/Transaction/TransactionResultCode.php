<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class TransactionResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SUCCESS = 'txSuccess'; // all operation succeeded
    public const FEE_BUMP_INNER_SUCCESS = 'txFeeBumpInnerSuccess'; // fee bump inner transaction succeeded
    public const FAILED = 'txFailed'; // one of the operations failed (none were applied)
    public const TOO_EARLY = 'txTooEarly'; // ledger closeTime before minTime
    public const TOO_LATE = 'txTooLate'; // ledger closeTime after maxTime
    public const MISSING_OPERATION = 'txMissingOperation'; // no operation was specified
    public const BAD_SEQ = 'txBadSeq'; // sequence number does not match source account
    public const BAD_AUTH = 'txBadAuth'; // too few valid signatures / wrong network
    public const INSUFFICIENT_BALANCE = 'txInsufficientBalance'; // fee would bring account below reserve
    public const NO_ACCOUNT = 'txNoAccount'; // source account was not found
    public const INSUFFICIENT_FEE = 'txInsufficientFee'; // fee is too small
    public const BAD_AUTH_EXTRA = 'txBadAuthExtra'; // unused signatures attached to transaction
    public const INTERNAL_ERROR = 'txInternalError'; // an unknown error occurred
    public const NOT_SUPPORTED = 'txNotSupported'; // transaction type not supported
    public const FEE_BUMP_INNER_FAILED = 'txFeeBumpInnerFailed'; // fee bump inner transaction failed
    public const BAD_SPONSORSHIP = 'txBadSponsorship'; // sponsorship not confirmed
    public const BAD_MIN_SEQ_AGE_OR_GAP = 'txBadMinSeqAgeOrGap'; // minSeqAge or minSeqLedgerGap conditions no met
    public const MALFORMED = 'txMalformed'; // precondition is invalid

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            1   => self::FEE_BUMP_INNER_SUCCESS,
            0   => self::SUCCESS,
            -1  => self::FAILED,
            -2  => self::TOO_EARLY,
            -3  => self::TOO_LATE,
            -4  => self::MISSING_OPERATION,
            -5  => self::BAD_SEQ,
            -6  => self::BAD_AUTH,
            -7  => self::INSUFFICIENT_BALANCE,
            -8  => self::NO_ACCOUNT,
            -9  => self::INSUFFICIENT_FEE,
            -10 => self::BAD_AUTH_EXTRA,
            -11 => self::INTERNAL_ERROR,
            -12 => self::NOT_SUPPORTED,
            -13 => self::FEE_BUMP_INNER_FAILED,
            -14 => self::BAD_SPONSORSHIP,
            -15 => self::BAD_MIN_SEQ_AGE_OR_GAP,
            -16 => self::MALFORMED,
        ];
    }

    /**
     * Return the selected result code.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as FEE_BUMP_INNER_SUCCESS.
     *
     * @return static
     */
    public static function feeBumpInnerSuccess(): static
    {
        return (new static())->withValue(self::FEE_BUMP_INNER_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::SUCCESS);
    }

    /**
     * Create a new instance pre-selected as FAILED.
     *
     * @return static
     */
    public static function failed(): static
    {
        return (new static())->withValue(self::FAILED);
    }

    /**
     * Create a new instance pre-selected as TOO_EARLY.
     *
     * @return static
     */
    public static function tooEarly(): static
    {
        return (new static())->withValue(self::TOO_EARLY);
    }

    /**
     * Create a new instance pre-selected as TOO_LATE.
     *
     * @return static
     */
    public static function tooLate(): static
    {
        return (new static())->withValue(self::TOO_LATE);
    }

    /**
     * Create a new instance pre-selected as MISSING_OPERATION.
     *
     * @return static
     */
    public static function missingOperation(): static
    {
        return (new static())->withValue(self::MISSING_OPERATION);
    }

    /**
     * Create a new instance pre-selected as BAD_SEQ.
     *
     * @return static
     */
    public static function badSeq(): static
    {
        return (new static())->withValue(self::BAD_SEQ);
    }

    /**
     * Create a new instance pre-selected as BAD_AUTH.
     *
     * @return static
     */
    public static function badAuth(): static
    {
        return (new static())->withValue(self::BAD_AUTH);
    }

    /**
     * Create a new instance pre-selected as INSUFFICIENT_BALANCE.
     *
     * @return static
     */
    public static function insufficientBalance(): static
    {
        return (new static())->withValue(self::INSUFFICIENT_BALANCE);
    }

    /**
     * Create a new instance pre-selected as NO_ACCOUNT.
     *
     * @return static
     */
    public static function noAccount(): static
    {
        return (new static())->withValue(self::NO_ACCOUNT);
    }

    /**
     * Create a new instance pre-selected as INSUFFICIENT_FEE.
     *
     * @return static
     */
    public static function insufficientFee(): static
    {
        return (new static())->withValue(self::INSUFFICIENT_FEE);
    }

    /**
     * Create a new instance pre-selected as BAD_AUTH_EXTRA.
     *
     * @return static
     */
    public static function badAuthExtra(): static
    {
        return (new static())->withValue(self::BAD_AUTH_EXTRA);
    }

    /**
     * Create a new instance pre-selected as INTERNAL_ERROR.
     *
     * @return static
     */
    public static function internalError(): static
    {
        return (new static())->withValue(self::INTERNAL_ERROR);
    }

    /**
     * Create a new instance pre-selected as NOT_SUPPORTED.
     *
     * @return static
     */
    public static function notSupported(): static
    {
        return (new static())->withValue(self::NOT_SUPPORTED);
    }

    /**
     * Create a new instance pre-selected as FEE_BUMP_INNER_FAILED.
     *
     * @return static
     */
    public static function feeBumpInnerFailed(): static
    {
        return (new static())->withValue(self::FEE_BUMP_INNER_FAILED);
    }

    /**
     * Create a new instance pre-selected as BAD_SPONSORSHIP.
     *
     * @return static
     */
    public static function badSponsorship(): static
    {
        return (new static())->withValue(self::BAD_SPONSORSHIP);
    }

    /**
     * Create a new instance pre-selected as BAD_MIN_SEQ_AGE_OR_GAP.
     *
     * @return static
     */
    public static function badMinSeqAgeOrGap(): static
    {
        return (new static())->withValue(self::BAD_MIN_SEQ_AGE_OR_GAP);
    }

    /**
     * Create a new instance pre-selected as MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::MALFORMED);
    }
}
