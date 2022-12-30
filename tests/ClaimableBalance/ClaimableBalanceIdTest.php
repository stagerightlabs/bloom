<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceIdType;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId
 */
class ClaimableBalanceIdTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(ClaimableBalanceIdType::class, ClaimableBalanceId::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            ClaimableBalanceIdType::CLAIMABLE_BALANCE_ID_TYPE_V0 => Hash::class,
        ];

        $this->assertEquals($expected, ClaimableBalanceId::arms());
    }

    /**
     * @test
     * @covers ::wrapHash
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_empty_memo()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));

        $this->assertInstanceOf(ClaimableBalanceId::class, $claimableBalanceId);
        $this->assertInstanceOf(Hash::class, $claimableBalanceId->unwrap());
    }
}
