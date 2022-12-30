<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

class OperationType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CREATE_ACCOUNT = 'createAccount';
    public const PAYMENT = 'payment';
    public const PATH_PAYMENT_STRICT_RECEIVE = 'pathPaymentStrictReceive';
    public const MANAGE_SELL_OFFER = 'manageSellOffer';
    public const CREATE_PASSIVE_SELL_OFFER = 'createPassiveSellOffer';
    public const SET_OPTIONS = 'setOptions';
    public const CHANGE_TRUST = 'changeTrust';
    public const ALLOW_TRUST = 'allowTrust';
    public const ACCOUNT_MERGE = 'accountMerge';
    public const INFLATION = 'inflation';
    public const MANAGE_DATA = 'manageData';
    public const BUMP_SEQUENCE = 'bumpSequence';
    public const MANAGE_BUY_OFFER = 'manageBuyOffer';
    public const PATH_PAYMENT_STRICT_SEND = 'pathPaymentStrictSend';
    public const CREATE_CLAIMABLE_BALANCE = 'createClaimableBalance';
    public const CLAIM_CLAIMABLE_BALANCE = 'claimClaimableBalance';
    public const BEGIN_SPONSORING_FUTURE_RESERVES = 'beginSponsoringFutureReserves';
    public const END_SPONSORING_FUTURE_RESERVES = 'endSponsoringFutureReserves';
    public const REVOKE_SPONSORSHIP = 'revokeSponsorship';
    public const CLAWBACK = 'clawback';
    public const CLAWBACK_CLAIMABLE_BALANCE = 'clawbackClaimableBalance';
    public const SET_TRUSTLINE_FLAGS = 'setTrustlineFlags';
    public const LIQUIDITY_POOL_DEPOSIT = 'liquidityPoolDeposit';
    public const LIQUIDITY_POOL_WITHDRAW = 'liquidityPoolWithdraw';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CREATE_ACCOUNT,
            1  => self::PAYMENT,
            2  => self::PATH_PAYMENT_STRICT_RECEIVE,
            3  => self::MANAGE_SELL_OFFER,
            4  => self::CREATE_PASSIVE_SELL_OFFER,
            5  => self::SET_OPTIONS,
            6  => self::CHANGE_TRUST,
            7  => self::ALLOW_TRUST,
            8  => self::ACCOUNT_MERGE,
            9  => self::INFLATION,
            10 => self::MANAGE_DATA,
            11 => self::BUMP_SEQUENCE,
            12 => self::MANAGE_BUY_OFFER,
            13 => self::PATH_PAYMENT_STRICT_SEND,
            14 => self::CREATE_CLAIMABLE_BALANCE,
            15 => self::CLAIM_CLAIMABLE_BALANCE,
            16 => self::BEGIN_SPONSORING_FUTURE_RESERVES,
            17 => self::END_SPONSORING_FUTURE_RESERVES,
            18 => self::REVOKE_SPONSORSHIP,
            19 => self::CLAWBACK,
            20 => self::CLAWBACK_CLAIMABLE_BALANCE,
            21 => self::SET_TRUSTLINE_FLAGS,
            22 => self::LIQUIDITY_POOL_DEPOSIT,
            23 => self::LIQUIDITY_POOL_WITHDRAW,
        ];
    }

    /**
     * Return the selected operation type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Set the selection from a value.
     *
     * @param string $type
     * @throws InvalidArgumentException
     * @return static
     */
    public function withType(string $type): static
    {
        return $this->withValue($type);
    }

    /**
     * Create a new instance pre-selected as CREATE_ACCOUNT.
     *
     * @return static
     */
    public static function createAccount(): static
    {
        return (new static())->withValue(self::CREATE_ACCOUNT);
    }

    /**
     * Create a new instance pre-selected as PAYMENT.
     *
     * @return static
     */
    public static function payment(): static
    {
        return (new static())->withValue(self::PAYMENT);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_RECEIVE.
     *
     * @return static
     */
    public static function pathPaymentStrictReceive(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_RECEIVE);
    }

    /**
     * Create a new instance pre-selected as MANAGE_SELL_OFFER.
     *
     * @return static
     */
    public static function manageSellOffer(): static
    {
        return (new static())->withValue(self::MANAGE_SELL_OFFER);
    }

    /**
     * Create a new instance pre-selected as CREATE_PASSIVE_SELL_OFFER.
     *
     * @return static
     */
    public static function createPassiveSellOffer(): static
    {
        return (new static())->withValue(self::CREATE_PASSIVE_SELL_OFFER);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS.
     *
     * @return static
     */
    public static function setOptions(): static
    {
        return (new static())->withValue(self::SET_OPTIONS);
    }

    /**
     * Create a new instance pre-selected as CHANGE_TRUST.
     *
     * @return static
     */
    public static function changeTrust(): static
    {
        return (new static())->withValue(self::CHANGE_TRUST);
    }

    /**
     * Create a new instance pre-selected as ALLOW_TRUST.
     *
     * @return static
     */
    public static function allowTrust(): static
    {
        return (new static())->withValue(self::ALLOW_TRUST);
    }

    /**
     * Create a new instance pre-selected as ACCOUNT_MERGE.
     *
     * @return static
     */
    public static function accountMerge(): static
    {
        return (new static())->withValue(self::ACCOUNT_MERGE);
    }

    /**
     * Create a new instance pre-selected as INFLATION.
     *
     * @return static
     */
    public static function inflation(): static
    {
        return (new static())->withValue(self::INFLATION);
    }

    /**
     * Create a new instance pre-selected as MANAGE_DATA.
     *
     * @return static
     */
    public static function manageData(): static
    {
        return (new static())->withValue(self::MANAGE_DATA);
    }

    /**
     * Create a new instance pre-selected as BUMP_SEQUENCE.
     *
     * @return static
     */
    public static function bumpSequence(): static
    {
        return (new static())->withValue(self::BUMP_SEQUENCE);
    }

    /**
     * Create a new instance pre-selected as MANAGE_BUY_OFFER.
     *
     * @return static
     */
    public static function manageBuyOffer(): static
    {
        return (new static())->withValue(self::MANAGE_BUY_OFFER);
    }

    /**
     * Create a new instance pre-selected as PATH_PAYMENT_STRICT_SEND.
     *
     * @return static
     */
    public static function pathPaymentStrictSend(): static
    {
        return (new static())->withValue(self::PATH_PAYMENT_STRICT_SEND);
    }

    /**
     * Create a new instance pre-selected as CREATE_CLAIMABLE_BALANCE.
     *
     * @return static
     */
    public static function createClaimableBalance(): static
    {
        return (new static())->withValue(self::CREATE_CLAIMABLE_BALANCE);
    }

    /**
     * Create a new instance pre-selected as CLAIM_CLAIMABLE_BALANCE.
     *
     * @return static
     */
    public static function claimClaimableBalance(): static
    {
        return (new static())->withValue(self::CLAIM_CLAIMABLE_BALANCE);
    }

    /**
     * Create a new instance pre-selected as BEGIN_SPONSORING_FUTURE_RESERVES.
     *
     * @return static
     */
    public static function beginSponsoringFutureReserves(): static
    {
        return (new static())->withValue(self::BEGIN_SPONSORING_FUTURE_RESERVES);
    }

    /**
     * Create a new instance pre-selected as END_SPONSORING_FUTURE_RESERVES.
     *
     * @return static
     */
    public static function endSponsoringFutureReserves(): static
    {
        return (new static())->withValue(self::END_SPONSORING_FUTURE_RESERVES);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP.
     *
     * @return static
     */
    public static function revokeSponsorship(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK.
     *
     * @return static
     */
    public static function clawback(): static
    {
        return (new static())->withValue(self::CLAWBACK);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_CLAIMABLE_BALANCE.
     *
     * @return static
     */
    public static function clawbackClaimableBalance(): static
    {
        return (new static())->withValue(self::CLAWBACK_CLAIMABLE_BALANCE);
    }

    /**
     * Create a new instance pre-selected as SET_TRUSTLINE_FLAGS.
     *
     * @return static
     */
    public static function setTrustlineFlags(): static
    {
        return (new static())->withValue(self::SET_TRUSTLINE_FLAGS);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_DEPOSIT.
     *
     * @return static
     */
    public static function liquidityPoolDeposit(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_DEPOSIT);
    }

    /**
     * Create a new instance pre-selected as LIQUIDITY_POOL_WITHDRAW.
     *
     * @return static
     */
    public static function liquidityPoolWithdraw(): static
    {
        return (new static())->withValue(self::LIQUIDITY_POOL_WITHDRAW);
    }
}
