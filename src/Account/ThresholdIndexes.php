<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ThresholdIndexes extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const THRESHOLD_MASTER_WEIGHT = 'thresholdMasterWeight';
    public const THRESHOLD_LOW = 'thresholdLow';
    public const THRESHOLD_MED = 'thresholdMed';
    public const THRESHOLD_HIGH = 'thresholdHigh';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::THRESHOLD_MASTER_WEIGHT,
            1 => self::THRESHOLD_LOW,
            2 => self::THRESHOLD_MED,
            3 => self::THRESHOLD_HIGH,
        ];
    }
}
