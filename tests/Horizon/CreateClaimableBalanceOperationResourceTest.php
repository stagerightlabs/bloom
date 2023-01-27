<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Horizon;

use StageRightLabs\Bloom\Horizon\ClaimantResource;
use StageRightLabs\Bloom\Horizon\CreateClaimableBalanceOperationResource;
use StageRightLabs\Bloom\Horizon\Response;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Horizon\CreateClaimableBalanceOperationResource
 */
class CreateClaimableBalanceOperationResourceTest extends TestCase
{
    /**
     * @test
     * @covers ::getAsset
     */
    public function it_returns_the_asset_string()
    {
        $operation = CreateClaimableBalanceOperationResource::wrap(
            Response::fake('create_claimable_balance_operation')->getBody()
        );

        $this->assertEquals(
            'NGNT:GAWODAROMJ33V5YDFY3NPYTHVYQG7MJXVJ2ND3AOGIHYRWINES6ACCPD',
            $operation->getAsset()
        );
    }

    /**
     * @test
     * @covers ::getAmount
     */
    public function it_returns_the_amount()
    {
        $operation = CreateClaimableBalanceOperationResource::wrap(
            Response::fake('create_claimable_balance_operation')->getBody()
        );

        $this->assertInstanceOf(ScaledAmount::class, $operation->getAmount());
        $this->assertNull((new CreateClaimableBalanceOperationResource())->getAmount());
    }

    /**
     * @test
     * @covers ::getClaimants
     */
    public function it_returns_an_array_of_claimants()
    {
        $operation = CreateClaimableBalanceOperationResource::wrap(
            Response::fake('create_claimable_balance_operation')->getBody()
        );

        foreach ($operation->getClaimants() as $claimant) {
            $this->assertInstanceOf(ClaimantResource::class, $claimant);
        }
    }
}
