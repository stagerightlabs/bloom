<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimableBalanceIdType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAIMABLE_BALANCE_ID_TYPE_V0 = 'claimableBalanceIdTypeV0';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::CLAIMABLE_BALANCE_ID_TYPE_V0,
        ];
    }

    /**
     * Return the selected claimable balance Id type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as CLAIMABLE_BALANCE_ID_TYPE_V0.
     *
     * @return static
     */
    public static function v0(): static
    {
        return (new static())->withValue(self::CLAIMABLE_BALANCE_ID_TYPE_V0);
    }
}
