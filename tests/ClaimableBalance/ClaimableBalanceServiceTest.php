<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\ClaimableBalance\Claimant;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceService
 */
class ClaimableBalanceServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::claimant
     */
    public function it_can_create_a_claimant_object()
    {
        $bloom = new Bloom();
        $claimant = $bloom->claimableBalance->claimant('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(Claimant::class, $claimant);
        $this->assertEquals(
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            $claimant->getAddress()
        );
    }
}
