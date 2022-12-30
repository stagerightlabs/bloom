<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class PathPaymentStrictSendResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const PATH_PAYMENT_STRICT_SEND_SUCCESS = 'pathPaymentStrictSendSuccess'; // success
    public const PATH_PAYMENT_STRICT_SEND_MALFORMED = 'pathPaymentStrictSendMalformed'; // bad input
    public const PATH_PAYMENT_STRICT_SEND_UNDERFUNDED = 'pathPaymentStrictSendUnderfunded'; // not enough funds in source account
    public const PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST = 'pathPaymentStrictSendSrcNoTrust'; // no trust line on source account
    public const PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED = 'pathPaymentStrictSendSrcNotAuthorized'; // source not authorized to transfer
    public const PATH_PAYMENT_STRICT_SEND_NO_DESTINATION = 'pathPaymentStrictSendNoDestination'; // destination account does not exist
    public const PATH_PAYMENT_STRICT_SEND_NO_TRUST = 'pathPaymentStrictSendNoTrust'; // destination missing a trust for asset
    public const PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED = 'pathPaymentStrictSendNotAuthorized'; // destination not authorized to hold asset
    public const PATH_PAYMENT_STRICT_SEND_LINE_FULL = 'pathPaymentStrictSendLineFull'; // destination would go above their limit
    public const PATH_PAYMENT_STRICT_SEND_NO_ISSUER = 'pathPaymentStrictSendNoIssuer'; // missing issuer on one asset
    public const PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS = 'pathPaymentStrictSend'; // not enough offers to satisfy path
    public const PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF = 'pathPaymentStrictSendOfferCrossSelf'; // would cross one of its own offers
    public const PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN = 'pathPaymentStrictSendUnderDestmin'; // could not satisfy destmin

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::PATH_PAYMENT_STRICT_SEND_SUCCESS,
            -1  => self::PATH_PAYMENT_STRICT_SEND_MALFORMED,
            -2  => self::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED,
            -3  => self::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST,
            -4  => self::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED,
            -5  => self::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION,
            -6  => self::PATH_PAYMENT_STRICT_SEND_NO_TRUST,
            -7  => self::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED,
            -8  => self::PATH_PAYMENT_STRICT_SEND_LINE_FULL,
            -9  => self::PATH_PAYMENT_STRICT_SEND_NO_ISSUER,
            -10 => self::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS,
            -11 => self::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF,
            -12 => self::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN,
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
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST.
     *
     * @return static
     */
    public static function srcNoTrust(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function srcNotAuthorized(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_NO_DESTINATION.
     *
     * @return static
     */
    public static function noDestination(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_NO_ISSUER.
     *
     * @return static
     */
    public static function noIssuer(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS.
     *
     * @return static
     */
    public static function tooFewOffers(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF.
     *
     * @return static
     */
    public static function offerCrossSelf(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN.
     *
     * @return static
     */
    public static function underDestmin(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN);
    }
}
