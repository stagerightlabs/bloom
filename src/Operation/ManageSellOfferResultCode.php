<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ManageSellOfferResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const MANAGE_SELL_OFFER_SUCCESS = 'manageSellOfferSuccess'; // success
    public const MANAGE_SELL_OFFER_MALFORMED = 'manageSellOfferMalformed'; // generated offer would be invalid
    public const MANAGE_SELL_OFFER_SELL_NO_TRUST = 'manageSellOfferSellNoTrust'; // no trust line for what it's selling
    public const MANAGE_SELL_OFFER_BUY_NO_TRUST = 'manageSellOfferBuyNoTrust'; // no trust line for what it's buying
    public const MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED = 'manageSellOfferSellNotAuthorized'; // not authorized to sell
    public const MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED = 'manageSellOfferBuyNotAuthorized'; // not authorized to buy
    public const MANAGE_SELL_OFFER_LINE_FULL = 'manageSellOfferLineFull'; // can't receive more of what it's buying
    public const MANAGE_SELL_OFFER_UNDERFUNDED = 'manageSellOfferUnderfunded'; // doesn't hold what it's trying to sell
    public const MANAGE_SELL_OFFER_CROSS_SELF = 'manageSellOfferCrossSelf'; // would cross an offer from the same user
    public const MANAGE_SELL_OFFER_SELL_NO_ISSUER = 'manageSellOfferSellNoIssuer'; // no issuer for what it's selling
    public const MANAGE_SELL_OFFER_BUY_NO_ISSUER = 'manageSellOfferBuyNoIssuer'; // no issuer for what it's buying
    public const MANAGE_SELL_OFFER_NOT_FOUND = 'manageSellOfferNotFound'; // offer Id does not match an existing offer
    public const MANAGE_SELL_OFFER_LOW_RESERVE = 'manageSellOfferLowReserve'; // not enough funds to create a new offer

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::MANAGE_SELL_OFFER_SUCCESS,
            -1  => self::MANAGE_SELL_OFFER_MALFORMED,
            -2  => self::MANAGE_SELL_OFFER_SELL_NO_TRUST,
            -3  => self::MANAGE_SELL_OFFER_BUY_NO_TRUST,
            -4  => self::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED,
            -5  => self::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED,
            -6  => self::MANAGE_SELL_OFFER_LINE_FULL,
            -7  => self::MANAGE_SELL_OFFER_UNDERFUNDED,
            -8  => self::MANAGE_SELL_OFFER_CROSS_SELF,
            -9  => self::MANAGE_SELL_OFFER_SELL_NO_ISSUER,
            -10 => self::MANAGE_SELL_OFFER_BUY_NO_ISSUER,
            -11 => self::MANAGE_SELL_OFFER_NOT_FOUND,
            -12 => self::MANAGE_SELL_OFFER_LOW_RESERVE,
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
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_SELL_NO_TRUST.
     *
     * @return static
     */
    public static function sellNoTrust(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_SELL_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_BUY_NO_TRUST.
     *
     * @return static
     */
    public static function buyNoTrust(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_BUY_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function sellNotAuthorized(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED.
     *
     * @return static
     */
    public static function buyNotAuthorized(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_LINE_FULL.
     *
     * @return static
     */
    public static function lineFull(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_LINE_FULL);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_UNDERFUNDED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_CROSS_SELF.
     *
     * @return static
     */
    public static function crossSelf(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_CROSS_SELF);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_SELL_NO_ISSUER.
     *
     * @return static
     */
    public static function sellNoIssuer(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_SELL_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_BUY_NO_ISSUER.
     *
     * @return static
     */
    public static function buyNoIssuer(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_BUY_NO_ISSUER);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_NOT_FOUND.
     *
     * @return static
     */
    public static function notFound(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_NOT_FOUND);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER_LOW_RESERVE);
    }
}
