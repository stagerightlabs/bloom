<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\Bloom;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateList;
use StageRightLabs\Bloom\ClaimableBalance\OptionalClaimPredicate;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateService
 */
class ClaimPredicateServiceTest extends TestCase
{
    /**
     * @test
     * @covers ::unconditional
     */
    public function it_can_create_an_unconditional_predicate()
    {
        $bloom = new Bloom();
        $claimPredicate = $bloom->predicate->unconditional();

        $this->assertInstanceOf(ClaimPredicate::class, $claimPredicate);
        $this->assertNull($claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::and
     */
    public function it_can_create_an_and_predicate()
    {
        $bloom = new Bloom();
        $predicate = $bloom->predicate->unconditional();
        $claimPredicate = $bloom->predicate->and([$predicate]);

        $this->assertInstanceOf(ClaimPredicate::class, $claimPredicate);
        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::or
     */
    public function it_can_create_an_or_predicate()
    {
        $bloom = new Bloom();
        $predicate = $bloom->predicate->unconditional();
        $claimPredicate = $bloom->predicate->or([$predicate]);

        $this->assertInstanceOf(ClaimPredicate::class, $claimPredicate);
        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::not
     */
    public function it_can_create_a_not_predicate()
    {
        $bloom = new Bloom();
        $predicate = $bloom->predicate->unconditional();
        $claimPredicate = $bloom->predicate->not($predicate);

        $this->assertInstanceOf(ClaimPredicate::class, $claimPredicate);
        $this->assertInstanceOf(OptionalClaimPredicate::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::beforeAbsoluteTime
     */
    public function it_can_create_a_before_absolute_time_predicate()
    {
        $bloom = new Bloom();
        $claimPredicate = $bloom->predicate->beforeAbsoluteTime(1640995200);

        $this->assertInstanceOf(Int64::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::beforeRelativeTime
     */
    public function it_can_create_a_before_relative_time_predicate()
    {
        $bloom = new Bloom();
        $claimPredicate = $bloom->predicate->beforeRelativeTime(100);

        $this->assertInstanceOf(Int64::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::collect
     */
    public function it_can_collect_a_group_of_predicates_into_a_list()
    {
        $bloom = new Bloom();
        $predicateA = $bloom->predicate->unconditional();
        $predicateB = $bloom->predicate->unconditional();
        $list = $bloom->predicate->collect([$predicateA, $predicateB]);

        $this->assertInstanceOf(ClaimPredicateList::class, $list);
        $this->assertCount(2, $list);
    }
}
