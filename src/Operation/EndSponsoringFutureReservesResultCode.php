<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class EndSponsoringFutureReservesResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const END_SPONSORING_FUTURE_RESERVES_SUCCESS = 'endSponsoringFutureReservesSuccess';
    public const END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED = 'endSponsoringFutureReservesNotSponsored';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::END_SPONSORING_FUTURE_RESERVES_SUCCESS,
            -1 => self::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED,
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
     * Create a new instance pre-selected as END_SPONSORING_FUTURE_RESERVES_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::END_SPONSORING_FUTURE_RESERVES_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED.
     *
     * @return static
     */
    public static function notSponsored(): static
    {
        return (new static())->withValue(self::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED);
    }
}
