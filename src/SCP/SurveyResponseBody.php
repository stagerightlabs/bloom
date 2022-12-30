<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class SurveyResponseBody extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return SurveyMessageCommandType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            SurveyMessageCommandType::SURVEY_TOPOLOGY => TopologyResponseBody::class,
        ];
    }

    /**
     * Create a new instance by wrapping a TopologyResponseBody object.
     *
     * @param TopologyResponseBody $topologyResponseBody
     * @return static
     */
    public static function wrapTopologyResponseBody(TopologyResponseBody $topologyResponseBody): static
    {
        $surveyResponseBody = new static();
        $surveyResponseBody->discriminator = SurveyMessageCommandType::surveyTopology();
        $surveyResponseBody->value = $topologyResponseBody;

        return $surveyResponseBody;
    }

    /**
     * Return the underlying value.
     *
     * @return TopologyResponseBody|null
     */
    public function unwrap(): ?TopologyResponseBody
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
