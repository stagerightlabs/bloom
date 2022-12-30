<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class RevokeSponsorshipResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const REVOKE_SPONSORSHIP_SUCCESS = 'revokeSponsorshipSuccess';
    public const REVOKE_SPONSORSHIP_DOES_NOT_EXIST = 'revokeSponsorshipDoesNotExist';
    public const REVOKE_SPONSORSHIP_NOT_SPONSOR = 'revokeSponsorshipNotSponsor';
    public const REVOKE_SPONSORSHIP_LOW_RESERVE = 'revokeSponsorshipLowReserve';
    public const REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE = 'revokeSponsorshipOnlyTransferable';
    public const REVOKE_SPONSORSHIP_MALFORMED = 'revokeSponsorshipMalformed';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::REVOKE_SPONSORSHIP_SUCCESS,
            -1 => self::REVOKE_SPONSORSHIP_DOES_NOT_EXIST,
            -2 => self::REVOKE_SPONSORSHIP_NOT_SPONSOR,
            -3 => self::REVOKE_SPONSORSHIP_LOW_RESERVE,
            -4 => self::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE,
            -5 => self::REVOKE_SPONSORSHIP_MALFORMED,
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
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_DOES_NOT_EXIST.
     *
     * @return static
     */
    public static function doesNotExist(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_DOES_NOT_EXIST);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_LOW_RESERVE.
     *
     * @return static
     */
    public static function lowReserve(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_LOW_RESERVE);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE.
     *
     * @return static
     */
    public static function onlyTransferable(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_MALFORMED);
    }
}
