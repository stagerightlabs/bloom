<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Offer;

use StageRightLabs\Bloom\Offer\ClaimAtomType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Offer\ClaimAtomType
 */
class ClaimAtomTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ClaimAtomType::CLAIM_ATOM_TYPE_V0,
            1 => ClaimAtomType::CLAIM_ATOM_TYPE_ORDER_BOOK,
            2 => ClaimAtomType::CLAIM_ATOM_TYPE_LIQUIDITY_POOL,
        ];
        $claimAtomType = new ClaimAtomType();

        $this->assertEquals($expected, $claimAtomType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $claimAtomType = ClaimAtomType::v0();
        $this->assertEquals(ClaimAtomType::CLAIM_ATOM_TYPE_V0, $claimAtomType->getType());
    }

    /**
     * @test
     * @covers ::v0
     */
    public function it_can_be_instantiated_as_a_v0_type()
    {
        $claimAtomType = ClaimAtomType::v0();

        $this->assertInstanceOf(ClaimAtomType::class, $claimAtomType);
        $this->assertEquals(ClaimAtomType::CLAIM_ATOM_TYPE_V0, $claimAtomType->getType());
    }

    /**
     * @test
     * @covers ::orderBook
     */
    public function it_can_be_instantiated_as_an_order_book_type()
    {
        $claimAtomType = ClaimAtomType::orderBook();

        $this->assertInstanceOf(ClaimAtomType::class, $claimAtomType);
        $this->assertEquals(ClaimAtomType::CLAIM_ATOM_TYPE_ORDER_BOOK, $claimAtomType->getType());
    }

    /**
     * @test
     * @covers ::liquidityPool
     */
    public function it_can_be_instantiated_as_a_liquidity_pool_type()
    {
        $claimAtomType = ClaimAtomType::liquidityPool();

        $this->assertInstanceOf(ClaimAtomType::class, $claimAtomType);
        $this->assertEquals(ClaimAtomType::CLAIM_ATOM_TYPE_LIQUIDITY_POOL, $claimAtomType->getType());
    }
}
