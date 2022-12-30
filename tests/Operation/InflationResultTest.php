<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\InflationPayout;
use StageRightLabs\Bloom\Operation\InflationResult;
use StageRightLabs\Bloom\Operation\InflationResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\InflationResult
 */
class InflationResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(InflationResultCode::class, InflationResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            InflationResultCode::INFLATION_SUCCESS  => InflationPayout::class,
            InflationResultCode::INFLATION_NOT_TIME => XDR::VOID,
        ];

        $this->assertEquals($expected, InflationResult::arms());
    }

    /**
     * @test
     * @covers ::wrapInflationPayout
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_inflation_payout()
    {
        $inflationResult = InflationResult::wrapInflationPayout(new InflationPayout());

        $this->assertInstanceOf(InflationResult::class, $inflationResult);
        $this->assertInstanceOf(InflationPayout::class, $inflationResult->unwrap());
        $this->assertEquals(InflationResultCode::INFLATION_SUCCESS, $inflationResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new InflationResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $inflationResult = InflationResult::simulate(InflationResultCode::notTime());

        $this->assertInstanceOf(InflationResult::class, $inflationResult);
        $this->assertEquals(InflationResultCode::INFLATION_NOT_TIME, $inflationResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $inflationResultA = InflationResult::wrapInflationPayout(new InflationPayout());
        $inflationResultB = new InflationResult();

        $this->assertTrue($inflationResultA->wasSuccessful());
        $this->assertFalse($inflationResultA->wasNotSuccessful());
        $this->assertTrue($inflationResultB->wasNotSuccessful());
        $this->assertFalse($inflationResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $inflationResult = InflationResult::simulate(InflationResultCode::notTime());

        $this->assertNotEmpty($inflationResult->getErrorMessage());
        $this->assertEquals('inflation_not_time', $inflationResult->getErrorCode());
        $this->assertNull((new InflationResult())->getErrorMessage());
        $this->assertNull((new InflationResult())->getErrorCode());
    }
}
