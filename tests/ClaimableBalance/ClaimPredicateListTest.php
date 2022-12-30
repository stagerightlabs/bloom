<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateList;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateList
 */
class ClaimPredicateListTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrType
     */
    public function it_defines_an_xdr_type()
    {
        $this->assertEquals(ClaimPredicate::class, ClaimPredicateList::getXdrType());
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_defines_a_max_length()
    {
        $this->assertEquals(ClaimPredicateList::MAX_LENGTH, ClaimPredicateList::getMaxLength());
    }

    /**
     * @test
     * @covers ::empty
     */
    public function it_can_be_instantiated_as_an_empty_list()
    {
        $signerList = ClaimPredicateList::empty();

        $this->assertInstanceOf(ClaimPredicateList::class, $signerList);
        $this->assertEmpty($signerList);
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_normalize_an_array_of_claim_predicates()
    {
        $arr = [new ClaimPredicate(), new ClaimPredicate(), null];
        $list = ClaimPredicateList::normalize($arr);

        $this->assertInstanceOf(ClaimPredicateList::class, $list);
        $this->assertCount(2, $list);
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function normalizing_a_claim_predicate_list_will_create_a_clone()
    {
        $listA = new ClaimPredicateList();
        $listB = ClaimPredicateList::normalize($listA);

        $this->assertInstanceOf(ClaimPredicateList::class, $listB);
        $this->assertNotEquals(spl_object_id($listA), spl_object_id($listB));
    }

    /**
     * @test
     * @covers ::normalize
     */
    public function it_can_create_a_list_from_a_single_claim_predicate()
    {
        $list = ClaimPredicateList::normalize(new ClaimPredicate());

        $this->assertInstanceOf(ClaimPredicateList::class, $list);
        $this->assertCount(1, $list);
    }
}
