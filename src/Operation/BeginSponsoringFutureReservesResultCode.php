<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class BeginSponsoringFutureReservesResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS = 'beginSponsoringFutureReservesSuccess';
    public const BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED = 'beginSponsoringFutureReservesMalformed';
    public const BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED = 'beginSponsoringFutureReservesAlreadySponsored';
    public const BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE = 'beginSponsoringFutureReservesRecursive';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS,
            -1 => self::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED,
            -2 => self::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED,
            -3 => self::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE,
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
     * Create a new instance pre-selected as BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED.
     *
     * @return static
     */
    public static function alreadySponsored(): static
    {
        return (new static())->withValue(self::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED);
    }

    /**
     * Create a new instance pre-selected as BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE.
     *
     * @return static
     */
    public static function recursive(): static
    {
        return (new static())->withValue(self::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE);
    }
}
