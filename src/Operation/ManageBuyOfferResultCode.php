<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ManageBuyOfferResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const MANAGE_BUY_OFFER_SUCCESS = 'manageBuyOfferSuccess';
    public const MANAGE_BUY_OFFER_MALFORMED = 'manageBuyOfferMalformed'; // generated offer would be invalid
    public const MANAGE_BUY_OFFER_SELL_NO_TRUST = 'manageBuyOfferSellNoTrust'; // no trust line for the asset being sold
    public const MANAGE_BUY_OFFER_BUY_NO_TRUST = 'manageBuyOfferBuyNoTrust'; // no trust line for the asset being bought
    public const MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED = 'manageBuyOfferSellNotAuthorized'; // not authorized to sell
    public const MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED = 'manageBuyOfferBuyNotAuthorized'; // not authorized to buy
    public const MANAGE_BUY_OFFER_LINE_FULL = 'manageBuyOfferLineFull'; // can't receive more of the asset being bought
    public const MANAGE_BUY_OFFER_UNDERFUNDED = 'manageBuyOfferUnderfunded'; // doesn't hold the asset being sold
    public const MANAGE_BUY_OFFER_CROSS_SELF = 'manageBuyOfferCrossSelf'; // would cross an offer from the same user
    public const MANAGE_BUY_OFFER_SELL_NO_ISSUER = 'manageBuyOfferSellNoIssuer'; // the asset being sold has no issuer
    public const MANAGE_BUY_OFFER_BUY_NO_ISSUER = 'manageBuyOfferBuyNoIssuer'; // the asset being bought has no issuer
    public const MANAGE_BUY_OFFER_NOT_FOUND = 'manageBuyOfferNotFound'; // offer Id does not match an existing offer
    public const MANAGE_BUY_OFFER_LOW_RESERVE = 'manageBuyOfferLowReserve'; // not enough funds to create a new offer

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::MANAGE_BUY_OFFER_SUCCESS,
            -1  => self::MANAGE_BUY_OFFER_MALFORMED,
            -2  => self::MANAGE_BUY_OFFER_SELL_NO_TRUST,
            -3  => self::MANAGE_BUY_OFFER_BUY_NO_TRUST,
            -4  => self::MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED,
            -5  => self::MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED,
            -6  => self::MANAGE_BUY_OFFER_LINE_FULL,
            -7  => self::MANAGE_BUY_OFFER_UNDERFUNDED,
            -8  => self::MANAGE_BUY_OFFER_CROSS_SELF,
            -9  => self::MANAGE_BUY_OFFER_SELL_NO_ISSUER,
            -10 => self::MANAGE_BUY_OFFER_BUY_NO_ISSUER,
            -11 => self::MANAGE_BUY_OFFER_NOT_FOUND,
            -12 => self::MANAGE_BUY_OFFER_LOW_RESERVE,
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
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_SELL_NO_TRUST.
     *
     * @return static
     */
    public static function sellNoTrust(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_SELL_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_BUY_NO_TRUST.
     *
     * @return static
     */
    public static function buyNoTrust(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_BUY_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function sellNotAuthorized(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_SELL_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function buyNotAuthorized(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_BUY_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_CROSS_SELF.
     *
     * @return static
     */
    public static function crossSelf(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_CROSS_SELF);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_SELL_NO_ISSUER.
     *
     * @return static
     */
    public static function sellNoIssuer(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_SELL_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_BUY_NO_ISSUER.
     *
     * @return static
     */
    public static function buyNoIssuer(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_BUY_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_NOT_FOUND.
     *
     * @return static
     */
    public static function notFound(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_NOT_FOUND);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER_LOW_RESERVE);
    }
}
