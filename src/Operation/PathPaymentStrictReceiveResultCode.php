<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class PathPaymentStrictReceiveResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const PATH_PAYMENT_STRICT_RECEIVE_SUCCESS = 'pathPaymentStrictReceiveSuccess'; // success
    public const PATH_PAYMENT_STRICT_RECEIVE_MALFORMED = 'pathPaymentStrictReceiveMalformed'; // bad input
    public const PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED = 'pathPaymentStrictReceiveUnderfunded'; // not enough funds in source account
    public const PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST = 'pathPaymentStrictReceiveSrcNoTrust'; // no trust line on source account
    public const PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED = 'pathPaymentStrictReceiveSrcNotAuthorized'; // source not authorized to transfer
    public const PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION = 'pathPaymentStrictReceiveNoDestination'; // destination account does not exist
    public const PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST = 'pathPaymentStrictReceiveNoTrust'; // dest missing a trust line for asset
    public const PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED = 'pathPaymentStrictReceiveNotAuthorized'; // dest not authorized to hold asset
    public const PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL = 'pathPaymentStrictReceiveLineFull'; // dest would go above their limit
    public const PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER = 'pathPaymentStrictReceiveNoIssuer'; // missing issuer on one asset ;
    public const PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS = 'pathPaymentStrictReceiveTooFewOffers'; // not enough offers to satisfy path
    public const PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF = 'pathPaymentStrictReceiveOffersCrossSelf'; // would cross one of its own offers
    public const PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX = 'pathPaymentStrictReceiveOverSendmax'; // could not satisfy sendmax

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS,
            -1  => self::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED,
            -2  => self::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED,
            -3  => self::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST,
            -4  => self::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED,
            -5  => self::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION,
            -6  => self::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST,
            -7  => self::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED,
            -8  => self::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL,
            -9  => self::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER,
            -10 => self::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS,
            -11 => self::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF,
            -12 => self::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX,
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
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST.
     *
     * @return static
     */
    public static function sourceNoTrust(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function sourceNotAuthorized(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION.
     *
     * @return static
     */
    public static function noDestination(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function notAuthorized(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER.
     *
     * @return static
     */
    public static function noIssuer(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS.
     *
     * @return static
     */
    public static function tooFewOffers(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSS_SELF.
     *
     * @return static
     */
    public static function offerCrossesSelf(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX.
     *
     * @return static
     */
    public static function overSendmax(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX);
    }
}
