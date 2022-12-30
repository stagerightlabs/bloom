<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResult;
use StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResult
 */
class EndSponsoringFutureReservesResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(EndSponsoringFutureReservesResultCode::class, EndSponsoringFutureReservesResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS       => XDR::VOID,
            EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED => XDR::VOID,
        ];

        $this->assertEquals($expected, EndSponsoringFutureReservesResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $endSponsoringFutureReservesResult = EndSponsoringFutureReservesResult::success();
        $this->assertEquals(EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS, $endSponsoringFutureReservesResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new EndSponsoringFutureReservesResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $endSponsoringFutureReservesResult = EndSponsoringFutureReservesResult::simulate(EndSponsoringFutureReservesResultCode::notSponsored());

        $this->assertInstanceOf(EndSponsoringFutureReservesResult::class, $endSponsoringFutureReservesResult);
        $this->assertEquals(EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED, $endSponsoringFutureReservesResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $endSponsoringFutureReservesResultA = EndSponsoringFutureReservesResult::success();
        $endSponsoringFutureReservesResultB = new EndSponsoringFutureReservesResult();

        $this->assertTrue($endSponsoringFutureReservesResultA->wasSuccessful());
        $this->assertFalse($endSponsoringFutureReservesResultA->wasNotSuccessful());
        $this->assertTrue($endSponsoringFutureReservesResultB->wasNotSuccessful());
        $this->assertFalse($endSponsoringFutureReservesResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $endSponsoringFutureReservesResult = EndSponsoringFutureReservesResult::simulate(EndSponsoringFutureReservesResultCode::notSponsored());

        $this->assertNotEmpty($endSponsoringFutureReservesResult->getErrorMessage());
        $this->assertEquals('end_sponsoring_future_reserves_not_sponsored', $endSponsoringFutureReservesResult->getErrorCode());
        $this->assertNull((new EndSponsoringFutureReservesResult())->getErrorMessage());
        $this->assertNull((new EndSponsoringFutureReservesResult())->getErrorCode());
    }
}
