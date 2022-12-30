<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\InflationResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\InflationResultCode
 */
class InflationResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => InflationResultCode::INFLATION_SUCCESS,
            -1 => InflationResultCode::INFLATION_NOT_TIME,
        ];
        $inflationResultCode = new InflationResultCode();

        $this->assertEquals($expected, $inflationResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $inflationResultCode = InflationResultCode::success();

        $this->assertInstanceOf(InflationResultCode::class, $inflationResultCode);
        $this->assertEquals(InflationResultCode::INFLATION_SUCCESS, $inflationResultCode->getType());
    }

    /**
     * @test
     * @covers ::notTime
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_time_type()
    {
        $inflationResultCode = InflationResultCode::notTime();

        $this->assertInstanceOf(InflationResultCode::class, $inflationResultCode);
        $this->assertEquals(InflationResultCode::INFLATION_NOT_TIME, $inflationResultCode->getType());
    }
}
