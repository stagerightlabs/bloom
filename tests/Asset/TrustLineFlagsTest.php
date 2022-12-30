<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Asset\TrustLineFlags;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Asset\TrustLineFlags
 */
class TrustLineFlagsTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            1 => TrustLineFlags::AUTHORIZED_FLAG,
            2 => TrustLineFlags::AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG,
            4 => TrustLineFlags::TRUSTLINE_CLAWBACK_ENABLED_FLAG,
        ];
        $trustLineFlags = new TrustLineFlags();

        $this->assertEquals($expected, $trustLineFlags->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $trustLineFlags = TrustLineFlags::authorized();
        $this->assertEquals(TrustLineFlags::AUTHORIZED_FLAG, $trustLineFlags->getType());
    }

    /**
     * @test
     * @covers ::toNativeInt
     */
    public function it_returns_an_integer_representation_of_the_selected_flag()
    {
        $this->assertEquals(1, TrustLineFlags::authorized()->toNativeInt());
        $this->assertEquals(2, TrustLineFlags::authorizedToMaintainLiabilities()->toNativeInt());
        $this->assertEquals(4, TrustLineFlags::trustlineClawbackEnabled()->toNativeInt());
    }

    /**
     * @test
     * @covers ::authorized
     */
    public function it_can_be_instantiated_as_an_authorized_flag()
    {
        $trustLineFlags = TrustLineFlags::authorized();

        $this->assertInstanceOf(TrustLineFlags::class, $trustLineFlags);
        $this->assertEquals(TrustLineFlags::AUTHORIZED_FLAG, $trustLineFlags->getType());
    }

    /**
     * @test
     * @covers ::authorizedToMaintainLiabilities
     */
    public function it_can_be_instantiated_as_an_authorized_to_maintain_liabilities_flag()
    {
        $trustLineFlags = TrustLineFlags::authorizedToMaintainLiabilities();

        $this->assertInstanceOf(TrustLineFlags::class, $trustLineFlags);
        $this->assertEquals(TrustLineFlags::AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG, $trustLineFlags->getType());
    }

    /**
     * @test
     * @covers ::trustlineClawbackEnabled
     */
    public function it_can_be_instantiated_as_a_trustline_clawback_enabled_flag()
    {
        $trustLineFlags = TrustLineFlags::trustlineClawbackEnabled();

        $this->assertInstanceOf(TrustLineFlags::class, $trustLineFlags);
        $this->assertEquals(TrustLineFlags::TRUSTLINE_CLAWBACK_ENABLED_FLAG, $trustLineFlags->getType());
    }
}
