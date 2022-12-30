<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateList;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateType;
use StageRightLabs\Bloom\ClaimableBalance\OptionalClaimPredicate;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate
 */
class ClaimPredicateTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClaimPredicateType::class, ClaimPredicate::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClaimPredicateType::CLAIM_PREDICATE_UNCONDITIONAL        => XDR::VOID,
            ClaimPredicateType::CLAIM_PREDICATE_AND                  => ClaimPredicateList::class,
            ClaimPredicateType::CLAIM_PREDICATE_OR                   => ClaimPredicateList::class,
            ClaimPredicateType::CLAIM_PREDICATE_NOT                  => OptionalClaimPredicate::class,
            ClaimPredicateType::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME => Int64::class,
            ClaimPredicateType::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME => Int64::class,
        ];

        $this->assertEquals($expected, ClaimPredicate::arms());
    }

    /**
     * @test
     * @covers ::unconditional
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_unconditional_claim_predicate()
    {
        $claimPredicate = ClaimPredicate::unconditional();
        $this->assertNull($claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::and
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_and_claim_predicate()
    {
        $claimPredicateList = new ClaimPredicateList();
        $claimPredicate = ClaimPredicate::and($claimPredicateList);

        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::and
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_and_claim_predicate_from_an_array()
    {
        $claimPredicate = ClaimPredicate::and([new ClaimPredicate(), new ClaimPredicate()]);
        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::or
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_or_claim_predicate()
    {
        $claimPredicateList = new ClaimPredicateList();
        $claimPredicate = ClaimPredicate::or($claimPredicateList);

        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::or
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_or_claim_predicate_from_an_array()
    {
        $claimPredicate = ClaimPredicate::or([new ClaimPredicate(), new ClaimPredicate()]);
        $this->assertInstanceOf(ClaimPredicateList::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::not
     */
    public function it_can_create_a_not_claim_predicate()
    {
        $claimPredicate = ClaimPredicate::not(new ClaimPredicate());
        $this->assertInstanceOf(OptionalClaimPredicate::class, $claimPredicate->unwrap());
    }

    /**
     * @test
     * @covers ::beforeAbsoluteTime
     * @covers ::unwrap
     */
    public function it_can_wrap_an_int64_as_an_absolute_time()
    {
        $claimPredicate = ClaimPredicate::beforeAbsoluteTime(Int64::of(1640995200));
        $this->assertInstanceOf(Int64::class, $claimPredicate->unwrap());
        $this->assertEquals(1640995200, $claimPredicate->unwrap()->toNativeInt());
    }

    /**
     * @test
     * @covers ::beforeAbsoluteTime
     * @covers ::unwrap
     */
    public function it_can_wrap_a_date_time_as_an_absolute_time()
    {
        $dateTime = new \DateTime("@1640995200");
        $claimPredicate = ClaimPredicate::beforeAbsoluteTime($dateTime);
        $this->assertInstanceOf(Int64::class, $claimPredicate->unwrap());
        $this->assertEquals(1640995200, $claimPredicate->unwrap()->toNativeInt());
    }

    /**
     * @test
     * @covers ::beforeRelativeTime
     * @covers ::unwrap
     */
    public function it_can_wrap_a_relative_time()
    {
        $claimPredicate = ClaimPredicate::beforeRelativeTime(Int64::of(100));
        $this->assertInstanceOf(Int64::class, $claimPredicate->unwrap());
    }
}
