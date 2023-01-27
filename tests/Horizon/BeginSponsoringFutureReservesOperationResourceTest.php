<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\BeginSponsoringFutureReservesOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\BeginSponsoringFutureReservesOperationResource
 */
class BeginSponsoringFutureReservesOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getSponsoredAccountAddress
     */
    public function it_returns_the_sponsored_account_address()
    {
        $operation = BeginSponsoringFutureReservesOperationResource::wrap(
            Response::fake('begin_sponsoring_future_reserves_operation')->getBody()
        );

        $this->assertEquals(
            'GC3C4AKRBQLHOJ45U4XG35ESVWRDECWO5XLDGYADO6DPR3L7KIDVUMML',
            $operation->getSponsoredAccountAddress()
        );
    }
}
