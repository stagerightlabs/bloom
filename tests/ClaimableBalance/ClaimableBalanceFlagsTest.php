<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceFlags;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceFlags
 */
class ClaimableBalanceFlagsTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => ClaimableBalanceFlags::CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG,
        ];
        $claimableBalanceFlags = new ClaimableBalanceFlags();

        $this->assertEquals($expected, $claimableBalanceFlags->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $claimableBalanceFlags = ClaimableBalanceFlags::clawbackEnabled();
        $this->assertEquals(ClaimableBalanceFlags::CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG, $claimableBalanceFlags->getType());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_an_integer_representation_of_the_selected_flag()
    {
        $this->assertEquals(1, ClaimableBalanceFlags::clawbackEnabled()->toNativeInt());
    }

    /**
     * @test
     * @covers ::clawbackEnabled
     */
    public function it_can_be_instantiated_as_a_clawbacks_enabled_flag()
    {
        $claimableBalanceFlags = ClaimableBalanceFlags::clawbackEnabled();

        $this->assertInstanceOf(ClaimableBalanceFlags::class, $claimableBalanceFlags);
        $this->assertEquals(ClaimableBalanceFlags::CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG, $claimableBalanceFlags->getType());
    }
}
