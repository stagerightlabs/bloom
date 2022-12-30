<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimantType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimantType
 */
class ClaimantTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ClaimantType::CLAIMANT_TYPE_V0,
        ];
        $claimantType = new ClaimantType();

        $this->assertEquals($expected, $claimantType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $claimantType = ClaimantType::v0();
        $this->assertEquals(ClaimantType::CLAIMANT_TYPE_V0, $claimantType->getType());
    }

    /**
     * @test
     * @covers ::v0
     */
    public function it_can_be_instantiated_as_a_v0_claimant_type()
    {
        $claimantType = ClaimantType::v0();

        $this->assertInstanceOf(ClaimantType::class, $claimantType);
        $this->assertEquals(ClaimantType::CLAIMANT_TYPE_V0, $claimantType->getType());
    }
}
