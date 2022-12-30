<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\BeginSponsoringFutureReservesResultCode
 */
class BeginSponsoringFutureReservesResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS,
            -1 => BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED,
            -2 => BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED,
            -3 => BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE,
        ];
        $beginSponsoringFutureReservesResultCode = new BeginSponsoringFutureReservesResultCode();

        $this->assertEquals($expected, $beginSponsoringFutureReservesResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $beginSponsoringFutureReservesResultCode = BeginSponsoringFutureReservesResultCode::success();

        $this->assertInstanceOf(BeginSponsoringFutureReservesResultCode::class, $beginSponsoringFutureReservesResultCode);
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS, $beginSponsoringFutureReservesResultCode->getType());
    }

    /**
     * @test
     * @covers ::malformed
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_malformed_type()
    {
        $beginSponsoringFutureReservesResultCode = BeginSponsoringFutureReservesResultCode::malformed();

        $this->assertInstanceOf(BeginSponsoringFutureReservesResultCode::class, $beginSponsoringFutureReservesResultCode);
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED, $beginSponsoringFutureReservesResultCode->getType());
    }

    /**
     * @test
     * @covers ::alreadySponsored
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_already_sponsored_type()
    {
        $beginSponsoringFutureReservesResultCode = BeginSponsoringFutureReservesResultCode::alreadySponsored();

        $this->assertInstanceOf(BeginSponsoringFutureReservesResultCode::class, $beginSponsoringFutureReservesResultCode);
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED, $beginSponsoringFutureReservesResultCode->getType());
    }

    /**
     * @test
     * @covers ::recursive
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_recursive_type()
    {
        $beginSponsoringFutureReservesResultCode = BeginSponsoringFutureReservesResultCode::recursive();

        $this->assertInstanceOf(BeginSponsoringFutureReservesResultCode::class, $beginSponsoringFutureReservesResultCode);
        $this->assertEquals(BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE, $beginSponsoringFutureReservesResultCode->getType());
    }
}
