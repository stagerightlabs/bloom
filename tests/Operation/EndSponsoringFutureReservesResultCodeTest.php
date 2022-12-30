<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResultCode;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\EndSponsoringFutureReservesResultCode
 */
class EndSponsoringFutureReservesResultCodeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS,
            -1 => EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED,
        ];
        $endSponsoringFutureReservesResultCode = new EndSponsoringFutureReservesResultCode();

        $this->assertEquals($expected, $endSponsoringFutureReservesResultCode->getOptions());
    }

    /**
     * @test
     * @covers ::success
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_success_type()
    {
        $endSponsoringFutureReservesResultCode = EndSponsoringFutureReservesResultCode::success();

        $this->assertInstanceOf(EndSponsoringFutureReservesResultCode::class, $endSponsoringFutureReservesResultCode);
        $this->assertEquals(EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS, $endSponsoringFutureReservesResultCode->getType());
    }

    /**
     * @test
     * @covers ::notSponsored
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_not_sponsored_type()
    {
        $endSponsoringFutureReservesResultCode = EndSponsoringFutureReservesResultCode::notSponsored();

        $this->assertInstanceOf(EndSponsoringFutureReservesResultCode::class, $endSponsoringFutureReservesResultCode);
        $this->assertEquals(EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED, $endSponsoringFutureReservesResultCode->getType());
    }
}
