<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\SurveyMessageCommandType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SurveyMessageCommandType
 */
class SurveyMessageCommandTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => SurveyMessageCommandType::SURVEY_TOPOLOGY,
        ];
        $surveyMessageCommandType = new SurveyMessageCommandType();

        $this->assertEquals($expected, $surveyMessageCommandType->getOptions());
    }

    /**
     * @test
     * @covers ::surveyTopology
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_survey_topology_type()
    {
        $surveyMessageCommandType = SurveyMessageCommandType::surveyTopology();

        $this->assertInstanceOf(SurveyMessageCommandType::class, $surveyMessageCommandType);
        $this->assertEquals(SurveyMessageCommandType::SURVEY_TOPOLOGY, $surveyMessageCommandType->getType());
    }
}
