<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class SetTrustLineFlagsResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SET_TRUST_LINE_FLAGS_SUCCESS = 'setTrustLineFlagsSuccess';
    public const SET_TRUST_LINE_FLAGS_MALFORMED = 'setTrustLineFlagsMalformed';
    public const SET_TRUST_LINE_FLAGS_NO_TRUST_LINE = 'setTrustLineFlagsNoTrustLine';
    public const SET_TRUST_LINE_FLAGS_CANT_REVOKE = 'setTrustLineFlagsCantRevoke';
    public const SET_TRUST_LINE_FLAGS_INVALID_STATE = 'setTrustLineFlagsInvalidState';
    public const SET_TRUST_LINE_FLAGS_LOW_RESERVE = 'setTrustLineFlagsLowReserve';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::SET_TRUST_LINE_FLAGS_SUCCESS,
            -1 => self::SET_TRUST_LINE_FLAGS_MALFORMED,
            -2 => self::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE,
            -3 => self::SET_TRUST_LINE_FLAGS_CANT_REVOKE,
            -4 => self::SET_TRUST_LINE_FLAGS_INVALID_STATE,
            -5 => self::SET_TRUST_LINE_FLAGS_LOW_RESERVE,
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
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_NO_TRUST_LINE.
     *
     * @return static
     */
    public static function noTrustLine(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE);
    }

    /**
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_CANT_REVOKE.
     *
     * @return static
     */
    public static function cantRevoke(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_CANT_REVOKE);
    }

    /**
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_INVALID_STATE.
     *
     * @return static
     */
    public static function invalidState(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_INVALID_STATE);
    }

    /**
     * Create a new instance pre-selected as SET_TRUST_LINE_FLAGS_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::SET_TRUST_LINE_FLAGS_LOW_RESERVE);
    }
}
