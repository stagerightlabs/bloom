<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\PreconditionType;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\PreconditionType
 */
class PreconditionTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => PreconditionType::PRECONDITION_NONE,
            1 => PreconditionType::PRECONDITION_TIME,
            2 => PreconditionType::PRECONDITION_V2,
        ];
        $preconditionType = new PreconditionType();

        $this->assertEquals($expected, $preconditionType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $preconditionType = PreconditionType::none();
        $this->assertEquals(PreconditionType::PRECONDITION_NONE, $preconditionType->getType());
    }

    /**
     * @test
     * @covers ::none
     */
    public function it_can_be_instantiated_as_a_none_type()
    {
        $preconditionType = PreconditionType::none();

        $this->assertInstanceOf(PreconditionType::class, $preconditionType);
        $this->assertEquals(PreconditionType::PRECONDITION_NONE, $preconditionType->getType());
    }

    /**
     * @test
     * @covers ::time
     */
    public function it_can_be_instantiated_as_a_time_type()
    {
        $preconditionType = PreconditionType::time();

        $this->assertInstanceOf(PreconditionType::class, $preconditionType);
        $this->assertEquals(PreconditionType::PRECONDITION_TIME, $preconditionType->getType());
    }

    /**
     * @test
     * @covers ::v2
     */
    public function it_can_be_instantiated_as_a_v2_type()
    {
        $preconditionType = PreconditionType::v2();

        $this->assertInstanceOf(PreconditionType::class, $preconditionType);
        $this->assertEquals(PreconditionType::PRECONDITION_V2, $preconditionType->getType());
    }
}
