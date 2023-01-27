<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\EndSponsoringFutureReservesOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\EndSponsoringFutureReservesOperationResource
 */
class EndSponsoringFutureReservesOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getSponsorAddress
     */
    public function it_returns_the_sponsor_address()
    {
        $operation = EndSponsoringFutureReservesOperationResource::wrap(
            Response::fake('end_sponsoring_future_reserves_operation')->getBody()
        );

        $this->assertEquals(
            'GAYOLLLUIZE4DZMBB2ZBKGBUBZLIOYU6XFLW37GBP2VZD3ABNXCW4BVA',
            $operation->getSponsorAddress()
        );
    }
}
