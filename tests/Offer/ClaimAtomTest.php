<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Offer\ClaimAtom;
use StageRightLabs\Bloom\Offer\ClaimAtomType;
use StageRightLabs\Bloom\Offer\ClaimLiquidityAtom;
use StageRightLabs\Bloom\Offer\ClaimOfferAtom;
use StageRightLabs\Bloom\Offer\ClaimOfferAtomV0;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimAtom
 */
class ClaimAtomTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClaimAtomType::class, ClaimAtom::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClaimAtomType::CLAIM_ATOM_TYPE_V0             => ClaimOfferAtomV0::class,
            ClaimAtomType::CLAIM_ATOM_TYPE_ORDER_BOOK     => ClaimOfferAtom::class,
            ClaimAtomType::CLAIM_ATOM_TYPE_LIQUIDITY_POOL => ClaimLiquidityAtom::class,
        ];

        $this->assertEquals($expected, ClaimAtom::arms());
    }

    /**
     * @test
     * @covers ::wrapClaimOfferAtomV0
     * @covers ::unwrap
     */
    public function it_can_be_wrap_a_claim_offer_atom_v0()
    {
        $claimOfferAtomV0 = new ClaimOfferAtomV0();
        $claimAtom = ClaimAtom::wrapClaimOfferAtomV0($claimOfferAtomV0);
        $this->assertInstanceOf(ClaimOfferAtomV0::class, $claimAtom->unwrap());
    }

    /**
     * @test
     * @covers ::wrapClaimOfferAtom
     * @covers ::unwrap
     */
    public function it_can_be_wrap_a_claim_offer_atom()
    {
        $claimOfferAtom = new ClaimOfferAtom();
        $claimAtom = ClaimAtom::wrapClaimOfferAtom($claimOfferAtom);
        $this->assertInstanceOf(ClaimOfferAtom::class, $claimAtom->unwrap());
    }

    /**
     * @test
     * @covers ::wrapClaimLiquidityAtom
     * @covers ::unwrap
     */
    public function it_can_be_wrap_a_claim_liquidity_atom()
    {
        $claimLiquidityAtom = new ClaimLiquidityAtom();
        $claimAtom = ClaimAtom::wrapClaimLiquidityAtom($claimLiquidityAtom);
        $this->assertInstanceOf(ClaimLiquidityAtom::class, $claimAtom->unwrap());
    }
}
