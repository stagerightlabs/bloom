<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\ScpStatementType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\ScpStatementType
 */
class ScpStatementTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ScpStatementType::SCP_ST_PREPARE,
            1 => ScpStatementType::SCP_ST_CONFIRM,
            2 => ScpStatementType::SCP_ST_EXTERNALIZE,
            3 => ScpStatementType::SCP_ST_NOMINATE,
        ];
        $scpStatementType = new ScpStatementType();

        $this->assertEquals($expected, $scpStatementType->getOptions());
    }

    /**
     * @test
     * @covers ::prepare
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_prepare_type()
    {
        $scpStatementType = ScpStatementType::prepare();

        $this->assertInstanceOf(ScpStatementType::class, $scpStatementType);
        $this->assertEquals(ScpStatementType::SCP_ST_PREPARE, $scpStatementType->getType());
    }

    /**
     * @test
     * @covers ::confirm
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_confirm_type()
    {
        $scpStatementType = ScpStatementType::confirm();

        $this->assertInstanceOf(ScpStatementType::class, $scpStatementType);
        $this->assertEquals(ScpStatementType::SCP_ST_CONFIRM, $scpStatementType->getType());
    }

    /**
     * @test
     * @covers ::externalize
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_externalize_type()
    {
        $scpStatementType = ScpStatementType::externalize();

        $this->assertInstanceOf(ScpStatementType::class, $scpStatementType);
        $this->assertEquals(ScpStatementType::SCP_ST_EXTERNALIZE, $scpStatementType->getType());
    }

    /**
     * @test
     * @covers ::nominate
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_nominate_type()
    {
        $scpStatementType = ScpStatementType::nominate();

        $this->assertInstanceOf(ScpStatementType::class, $scpStatementType);
        $this->assertEquals(ScpStatementType::SCP_ST_NOMINATE, $scpStatementType->getType());
    }
}
