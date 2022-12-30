<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResult;
use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResultCode;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResult
 */
class BeginSponsoringFutureReservesResultTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::class, BeginSponsoringFutureReservesResult::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS           => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED         => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE         => XDR::VOID,
        ];

        $this->assertEquals($expected, BeginSponsoringFutureReservesResult::arms());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_union()
    {
        $beginSponsoringFutureReservesResult = BeginSponsoringFutureReservesResult::success();
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS, $beginSponsoringFutureReservesResult->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new BeginSponsoringFutureReservesResult())->getType());
    }

    /**
     * @test
     * @covers ::simulate
     */
    public function it_can_simulate_an_error_result()
    {
        $beginSponsoringFutureReservesResult = BeginSponsoringFutureReservesResult::simulate(BeginSponsoringFutureReservesResultCode::malformed());

        $this->assertInstanceOf(BeginSponsoringFutureReservesResult::class, $beginSponsoringFutureReservesResult);
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED, $beginSponsoringFutureReservesResult->getType());
    }

    /**
     * @test
     * @covers ::wasSuccessful
     * @covers ::wasNotSuccessful
     */
    public function it_knows_if_it_was_successful()
    {
        $beginSponsoringFutureReservesResultA = BeginSponsoringFutureReservesResult::success();
        $beginSponsoringFutureReservesResultB = new BeginSponsoringFutureReservesResult();

        $this->assertTrue($beginSponsoringFutureReservesResultA->wasSuccessful());
        $this->assertFalse($beginSponsoringFutureReservesResultA->wasNotSuccessful());
        $this->assertTrue($beginSponsoringFutureReservesResultB->wasNotSuccessful());
        $this->assertFalse($beginSponsoringFutureReservesResultB->wasSuccessful());
    }

    /**
     * @test
     * @covers ::getErrorMessage
     * @covers ::getErrorCode
     */
    public function it_returns_an_error_message_and_code()
    {
        $beginSponsoringFutureReservesResult = BeginSponsoringFutureReservesResult::simulate(BeginSponsoringFutureReservesResultCode::malformed());

        $this->assertNotEmpty($beginSponsoringFutureReservesResult->getErrorMessage());
        $this->assertNull((new BeginSponsoringFutureReservesResult())->getErrorMessage());
        $this->assertEquals('begin_sponsoring_future_reserves_malformed', $beginSponsoringFutureReservesResult->getErrorCode());
        $this->assertNull((new BeginSponsoringFutureReservesResult())->getErrorCode());
    }
}
