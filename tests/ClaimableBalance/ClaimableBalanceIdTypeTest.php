<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceIdType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceIdType
 */
class ClaimableBalanceIdTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ClaimableBalanceIdType::CLAIMABLE_BALANCE_ID_TYPE_V0,
        ];
        $claimableBalanceIdType = new ClaimableBalanceIdType();

        $this->assertEquals($expected, $claimableBalanceIdType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $claimableBalanceIdType = ClaimableBalanceIdType::v0();
        $this->assertEquals(ClaimableBalanceIdType::CLAIMABLE_BALANCE_ID_TYPE_V0, $claimableBalanceIdType->getType());
    }

    /**
     * @test
     * @covers ::v0
     */
    public function it_can_be_instantiated_as_a_v0_type()
    {
        $claimableBalanceIdType = ClaimableBalanceIdType::v0();

        $this->assertInstanceOf(ClaimableBalanceIdType::class, $claimableBalanceIdType);
        $this->assertEquals(ClaimableBalanceIdType::CLAIMABLE_BALANCE_ID_TYPE_V0, $claimableBalanceIdType->getType());
    }
}
