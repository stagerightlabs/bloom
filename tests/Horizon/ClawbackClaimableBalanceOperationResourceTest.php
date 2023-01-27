<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ClawbackClaimableBalanceOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\ClawbackClaimableBalanceOperationResource
 */
class ClawbackClaimableBalanceOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getBalanceId
     */
    public function it_returns_the_balance_id()
    {
        $operation = ClawbackClaimableBalanceOperationResource::wrap(
            Response::fake('clawback_claimable_balance_operation')->getBody()
        );

        $this->assertEquals(
            '000000000102030000000000000000000000000000000000000000000000000000000000',
            $operation->getBalanceId()
        );
    }
}
