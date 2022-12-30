<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClawbackResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAWBACK_SUCCESS = 'clawbackSuccess';
    public const CLAWBACK_MALFORMED = 'clawbackMalformed';
    public const CLAWBACK_NOT_CLAWBACK_ENABLED = 'clawbackNotClawbackEnabled';
    public const CLAWBACK_NO_TRUST = 'clawbackNoTrust';
    public const CLAWBACK_UNDERFUNDED = 'clawbackUnderfunded';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::CLAWBACK_SUCCESS,
            -1 => self::CLAWBACK_MALFORMED,
            -2 => self::CLAWBACK_NOT_CLAWBACK_ENABLED,
            -3 => self::CLAWBACK_NO_TRUST,
            -4 => self::CLAWBACK_UNDERFUNDED,
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
     * Create a new instance pre-selected as CLAWBACK_SUCCESS.
     *
     * @return static
     */
    public static function success(): static
    {
        return (new static())->withValue(self::CLAWBACK_SUCCESS);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_MALFORMED.
     *
     * @return static
     */
    public static function malformed(): static
    {
        return (new static())->withValue(self::CLAWBACK_MALFORMED);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_NOT_CLAWBACK_ENABLED.
     *
     * @return static
     */
    public static function notClawbackEnabled(): static
    {
        return (new static())->withValue(self::CLAWBACK_NOT_CLAWBACK_ENABLED);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_NO_TRUST.
     *
     * @return static
     */
    public static function noTrust(): static
    {
        return (new static())->withValue(self::CLAWBACK_NO_TRUST);
    }

    /**
     * Create a new instance pre-selected as CLAWBACK_UNDERFUNDED.
     *
     * @return static
     */
    public static function underfunded(): static
    {
        return (new static())->withValue(self::CLAWBACK_UNDERFUNDED);
    }
}
