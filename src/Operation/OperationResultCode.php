<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class OperationResultCode extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const INNER = 'opInner'; // Inner object result is valid
    public const BAD_AUTH = 'opBadAuth'; // too few valid signatures / wrong network
    public const NO_ACCOUNT = 'opNoAccount'; // source account was not found
    public const NOT_SUPPORTED = 'opNotSupported'; // operation not supported at this time
    public const TOO_MANY_SUBENTRIES = 'opTooManySubentries'; // Max number of subentries exceeded
    public const EXCEEDED_WORK_LIMIT = 'opExceededWorkLimit'; // operation did too much work
    public const TOO_MANY_SPONSORING = 'opTooManySponsoring'; // account is sponsoring too many entries

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::INNER,
            -1 => self::BAD_AUTH,
            -2 => self::NO_ACCOUNT,
            -3 => self::NOT_SUPPORTED,
            -4 => self::TOO_MANY_SUBENTRIES,
            -5 => self::EXCEEDED_WORK_LIMIT,
            -6 => self::TOO_MANY_SPONSORING,
        ];
    }

    /**
     * Return the selected operation result type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as INNER.
     *
     * @return static
     */
    public static function inner(): static
    {
        return (new static())->withValue(self::INNER);
    }

    /**
     * Create a new instance pre-selected as BAD_AUTH.
     *
     * @return static
     */
    public static function badAuth(): static
    {
        return (new static())->withValue(self::BAD_AUTH);
    }

    /**
     * Create a new instance pre-selected as NO_ACCOUNT.
     *
     * @return static
     */
    public static function noAccount(): static
    {
        return (new static())->withValue(self::NO_ACCOUNT);
    }

    /**
     * Create a new instance pre-selected as NOT_SUPPORTED.
     *
     * @return static
     */
    public static function notSupported(): static
    {
        return (new static())->withValue(self::NOT_SUPPORTED);
    }

    /**
     * Create a new instance pre-selected as TOO_MANY_SUBENTRIES.
     *
     * @return static
     */
    public static function tooManySubentries(): static
    {
        return (new static())->withValue(self::TOO_MANY_SUBENTRIES);
    }

    /**
     * Create a new instance pre-selected as EXCEEDED_WORK_LIMIT.
     *
     * @return static
     */
    public static function exceededWorkLimit(): static
    {
        return (new static())->withValue(self::EXCEEDED_WORK_LIMIT);
    }

    /**
     * Create a new instance pre-selected as TOO_MANY_SPONSORING.
     *
     * @return static
     */
    public static function tooManySponsoring(): static
    {
        return (new static())->withValue(self::TOO_MANY_SPONSORING);
    }
}
