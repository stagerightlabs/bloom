<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class SetOptionsResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SET_OPTIONS_SUCCESS = 'setOptionsSuccess';
    public const SET_OPTIONS_LOW_RESERVE = 'setOptionsLowReserve'; // not enough funds to add a signer
    public const SET_OPTIONS_TOO_MANY_SIGNERS = 'setOptionsTooManySigners'; // max number of signers already reached
    public const SET_OPTIONS_BAD_FLAGS = 'setOptionsBadFlags'; // invalid combination of clear/set flags
    public const SET_OPTIONS_INVALID_INFLATION = 'setOptionsInvalidInflation'; // inflation account does not exist
    public const SET_OPTIONS_CANT_CHANGE = 'setOptionsCantChange'; // can no longer change this option
    public const SET_OPTIONS_UNKNOWN_FLAG = 'setOptionsUnknownFlag'; // can't set an unknown flag
    public const SET_OPTIONS_THRESHOLD_OUT_OF_RANGE = 'setOptionsThresholdOutOfRange'; // bad value for weight/threshold
    public const SET_OPTIONS_BAD_SIGNER = 'setOptionsBadSigner'; // signer cannot be master key
    public const SET_OPTIONS_INVALID_HOME_DOMAIN = 'setOptionsInvalidHomeDomain'; // malformed home domain
    public const SET_OPTIONS_AUTH_REVOCABLE_REQUIRED = 'setOptionsAuthRevocableRequired'; // auth revocable is required for clawback

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0   => self::SET_OPTIONS_SUCCESS,
            -1  => self::SET_OPTIONS_LOW_RESERVE,
            -2  => self::SET_OPTIONS_TOO_MANY_SIGNERS,
            -3  => self::SET_OPTIONS_BAD_FLAGS,
            -4  => self::SET_OPTIONS_INVALID_INFLATION,
            -5  => self::SET_OPTIONS_CANT_CHANGE,
            -6  => self::SET_OPTIONS_UNKNOWN_FLAG,
            -7  => self::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE,
            -8  => self::SET_OPTIONS_BAD_SIGNER,
            -9  => self::SET_OPTIONS_INVALID_HOME_DOMAIN,
            -10 => self::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED,
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
     * Create a new instance pre-selected as SET_OPTIONS_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_TOO_MANY_SIGNERS.
     *
     * @return static
     */
    public static function tooManySigners(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_TOO_MANY_SIGNERS);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_BAD_FLAGS.
     *
     * @return static
     */
    public static function badFlags(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_BAD_FLAGS);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_INVALID_INFLATION.
     *
     * @return static
     */
    public static function invalidInflation(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_INVALID_INFLATION);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_CANT_CHANGE.
     *
     * @return static
     */
    public static function cantChange(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_CANT_CHANGE);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_UNKNOWN_FLAG.
     *
     * @return static
     */
    public static function unknownFlag(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_UNKNOWN_FLAG);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_THRESHOLD_OUT_OF_RANGE.
     *
     * @return static
     */
    public static function thresholdOutOfRange(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_BAD_SIGNER.
     *
     * @return static
     */
    public static function badSigner(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_BAD_SIGNER);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_INVALID_HOME_DOMAIN.
     *
     * @return static
     */
    public static function invalidHomeDomain(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_INVALID_HOME_DOMAIN);
    }

    /**
     * Create a new instance pre-selected as SET_OPTIONS_AUTH_REVOCABLE_REQUIRED.
     *
     * @return static
     */
    public static function authRevocableRequired(): static
    {
        return (new static())->withValue(self::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED);
    }
}
