<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\Claimant;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantType;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantV0;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\Claimant
 */
class ClaimantTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClaimantType::class, Claimant::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClaimantType::CLAIMANT_TYPE_V0 => ClaimantV0::class,
        ];

        $this->assertEquals($expected, Claimant::arms());
    }

    /**
     * @test
     * @covers ::wrapClaimantV0
     * @covers ::unwrap
     */
    public function it_can_wrap_a_claimant_v0()
    {
        $claimantV0 = new ClaimantV0();
        $claimant = (new Claimant())->wrapClaimantV0($claimantV0);

        $this->assertInstanceOf(Claimant::class, $claimant);
        $this->assertInstanceOf(ClaimantV0::class, $claimant->unwrap());
    }

    /**
     * @test
     * @covers ::fromAddressable
     * @covers ::getAddress
     */
    public function it_can_be_created_from_an_addressable()
    {
        $claimant = Claimant::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');

        $this->assertInstanceOf(Claimant::class, $claimant);
        $this->assertEquals(
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            $claimant->getAddress()
        );
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_a_null_address_if_no_value_is_set()
    {
        $this->assertNull((new Claimant())->getAddress());
    }
}
