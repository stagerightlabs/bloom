<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateType
 */
class ClaimPredicateTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => ClaimPredicateType::CLAIM_PREDICATE_UNCONDITIONAL,
            1 => ClaimPredicateType::CLAIM_PREDICATE_AND,
            2 => ClaimPredicateType::CLAIM_PREDICATE_OR,
            3 => ClaimPredicateType::CLAIM_PREDICATE_NOT,
            4 => ClaimPredicateType::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME,
            5 => ClaimPredicateType::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME,
        ];
        $claimPredicateType = new ClaimPredicateType();

        $this->assertEquals($expected, $claimPredicateType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $claimPredicateType = ClaimPredicateType::unconditional();
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_UNCONDITIONAL, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::unconditional
     */
    public function it_can_be_instantiated_as_an_unconditional_type()
    {
        $claimPredicateType = ClaimPredicateType::unconditional();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_UNCONDITIONAL, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::and
     */
    public function it_can_be_instantiated_as_an_and_type()
    {
        $claimPredicateType = ClaimPredicateType::and();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_AND, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::or
     */
    public function it_can_be_instantiated_as_an_or_type()
    {
        $claimPredicateType = ClaimPredicateType::or();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_OR, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::not
     */
    public function it_can_be_instantiated_as_a_not_type()
    {
        $claimPredicateType = ClaimPredicateType::not();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_NOT, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::beforeAbsoluteTime
     */
    public function it_can_be_instantiated_as_a_before_absolute_time_type()
    {
        $claimPredicateType = ClaimPredicateType::beforeAbsoluteTime();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_BEFORE_ABSOLUTE_TIME, $claimPredicateType->getType());
    }

    /**
     * @test
     * @covers ::beforeRelativeTime
     */
    public function it_can_be_instantiated_as_a_before_relative_time_type()
    {
        $claimPredicateType = ClaimPredicateType::beforeRelativeTime();

        $this->assertInstanceOf(ClaimPredicateType::class, $claimPredicateType);
        $this->assertEquals(ClaimPredicateType::CLAIM_PREDICATE_BEFORE_RELATIVE_TIME, $claimPredicateType->getType());
    }
}
