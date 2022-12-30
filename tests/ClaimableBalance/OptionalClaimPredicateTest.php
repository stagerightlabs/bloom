<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate;
use StageRightLabs\Bloom\ClaimableBalance\OptionalClaimPredicate;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\OptionalClaimPredicate
 */
class Test extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(ClaimPredicate::class, OptionalClaimPredicate::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_claim_predicate()
    {
        $claimPredicate = new ClaimPredicate();
        $optional = OptionalClaimPredicate::some($claimPredicate);

        $this->assertInstanceOf(OptionalClaimPredicate::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_account_id()
    {
        $claimPredicate = new ClaimPredicate();
        $optional = OptionalClaimPredicate::some($claimPredicate);

        $this->assertInstanceOf(ClaimPredicate::class, $optional->unwrap());
    }
}
