<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class SurveyMessageCommandType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const SURVEY_TOPOLOGY = 'surveyTopology';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::SURVEY_TOPOLOGY,
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
     * Create a new instance pre-selected as SURVEY_TOPOLOGY.
     *
     * @return static
     */
    public static function surveyTopology(): static
    {
        return (new static())->withValue(self::SURVEY_TOPOLOGY);
    }
}
