<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ClaimantType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const CLAIMANT_TYPE_V0 = 'claimantTypeV0';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::CLAIMANT_TYPE_V0,
        ];
    }

    /**
     * Return the selected memo type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as CLAIMANT_TYPE_V0.
     *
     * @return static
     */
    public static function v0(): static
    {
        return (new static())->withValue(self::CLAIMANT_TYPE_V0);
    }
}
