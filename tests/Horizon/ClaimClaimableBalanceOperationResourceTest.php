<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ClaimClaimableBalanceOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ClaimClaimableBalanceOperationResource
 */
class ClaimClaimableBalanceOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getBalanceId
     */
    public function it_returns_the_balance_id()
    {
        $operation = ClaimClaimableBalanceOperationResource::wrap(
            Response::fake('claim_claimable_balance_operation')->getBody()
        );

        $this->assertEquals(
            '000000000102030000000000000000000000000000000000000000000000000000000000',
            $operation->getBalanceId()
        );
    }

    /**
     * @test
     * @covers ::getClaimantAddress
     */
    public function it_returns_the_claimant_address()
    {
        $operation = ClaimClaimableBalanceOperationResource::wrap(
            Response::fake('claim_claimable_balance_operation')->getBody()
        );

        $this->assertEquals(
            'GC3C4AKRBQLHOJ45U4XG35ESVWRDECWO5XLDGYADO6DPR3L7KIDVUMML',
            $operation->getClaimantAddress()
        );
    }
}
