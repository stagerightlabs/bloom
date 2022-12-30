<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class PaymentResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const PAYMENT_SUCCESS = 'paymentSuccess'; // payment successfully completed
    public const PAYMENT_MALFORMED = 'paymentMalformed'; // bad input
    public const PAYMENT_UNDERFUNDED = 'paymentUnderfunded'; // not enough funds in source account
    public const PAYMENT_SRC_NO_TRUST = 'paymentSrcNoTrust'; // no trust line on source account
    public const PAYMENT_SRC_NOT_AUTHORIZED = 'paymentSrcNotAuthorized'; // source not authorized to transfer
    public const PAYMENT_NO_DESTINATION = 'paymentNoDestination'; // destination account does not exist
    public const PAYMENT_NO_TRUST = 'paymentNoTrust'; // destination missing a trust line for asset
    public const PAYMENT_NOT_AUTHORIZED = 'paymentNotAuthorized'; // destination not authorized to hold asset
    public const PAYMENT_LINE_FULL = 'paymentLineFull'; // destination would go above their limit
    public const PAYMENT_NO_ISSUER = 'paymentNoIssuer'; // missing issuer on asset

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::PAYMENT_SUCCESS,
            -1 => self::PAYMENT_MALFORMED,
            -2 => self::PAYMENT_UNDERFUNDED,
            -3 => self::PAYMENT_SRC_NO_TRUST,
            -4 => self::PAYMENT_SRC_NOT_AUTHORIZED,
            -5 => self::PAYMENT_NO_DESTINATION,
            -6 => self::PAYMENT_NO_TRUST,
            -7 => self::PAYMENT_NOT_AUTHORIZED,
            -8 => self::PAYMENT_LINE_FULL,
            -9 => self::PAYMENT_NO_ISSUER,
        ];
    }

    /**
     * Return the selected result type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as PAYMENT_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::PAYMENT_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::PAYMENT_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::PAYMENT_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_SRC_NO_TRUST.
     *
     * @return static
     */
    public static function sourceNoTrust(): static
    {
        return (new static())->withValue(self::PAYMENT_SRC_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_SRC_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function sourceNotAuthorized(): static
    {
        return (new static())->withValue(self::PAYMENT_SRC_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_NO_DESTINATION.
     *
     * @return static
     */
    public static function noDestination(): static
    {
        return (new static())->withValue(self::PAYMENT_NO_DESTINATION);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::PAYMENT_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::PAYMENT_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::PAYMENT_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as PAYMENT_NO_ISSUER.
     *
     * @return static
     */
    public static function noIssuer(): static
    {
        return (new static())->withValue(self::PAYMENT_NO_ISSUER);
    }
}
