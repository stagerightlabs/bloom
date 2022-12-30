<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\Claimant;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimantList
 */
class ClaimantListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(Claimant::class, ClaimantList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ClaimantList::MAX_LENGTH, ClaimantList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $claimantList = ClaimantList::empty();

        $this->assertInstanceOf(ClaimantList::class, $claimantList);
        $this->assertEmpty($claimantList);
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_be_instantiated_from_an_array_of_claimants()
    {
        $arr = [
            Claimant::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'),
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS',
            'GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS'
        ];
        $claimantList = ClaimantList::normalize($arr);

        $this->assertInstanceOf(ClaimantList::class, $claimantList);
        $this->assertCount(3, $claimantList);
        foreach ($claimantList as $asset) {
            $this->assertInstanceOf(Claimant::class, $asset);
        }
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_be_instantiated_from_a_single_claimant()
    {
        $claimant = Claimant::fromAddressable('GBK4AH4HI7O3ZBVG2DFY3CRHA5KLVWLHJHAXXXTICKQHTX66VF5FE7IS');
        $claimantList = ClaimantList::normalize($claimant);

        $this->assertInstanceOf(ClaimantList::class, $claimantList);
        $this->assertCount(1, $claimantList);
        foreach ($claimantList as $asset) {
            $this->assertInstanceOf(Claimant::class, $asset);
        }
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_an_instance_of_itself()
    {
        $arr = ClaimantList::empty();
        $copy = ClaimantList::normalize($arr);

        $this->assertNotEquals(spl_object_id($arr), spl_object_id($copy));
    }
}
