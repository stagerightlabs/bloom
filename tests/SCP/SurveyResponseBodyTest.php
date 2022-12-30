<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\SurveyMessageCommandType;
use StageRightLabs\Bloom\SCP\SurveyResponseBody;
use StageRightLabs\Bloom\SCP\TopologyResponseBody;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SurveyResponseBody
 */
class SurveyResponseBodyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(SurveyMessageCommandType::class, SurveyResponseBody::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            SurveyMessageCommandType::SURVEY_TOPOLOGY => TopologyResponseBody::class,
        ];

        $this->assertEquals($expected, SurveyResponseBody::arms());
    }

    /**
     * @test
     * @covers ::wrapTopologyResponseBody
     * @covers ::unwrap
     */
    public function it_can_wrap_a_topology_response_body()
    {
        $surveyResponseBody = SurveyResponseBody::wrapTopologyResponseBody(new TopologyResponseBody());

        $this->assertInstanceOf(SurveyResponseBody::class, $surveyResponseBody);
        $this->assertInstanceOf(TopologyResponseBody::class, $surveyResponseBody->unwrap());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new SurveyResponseBody())->unwrap());
    }
}
