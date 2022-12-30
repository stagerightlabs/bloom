<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\OptionalAccountId;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\OptionalAccountId
 */
class OptionalAccountIdTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(AccountId::class, OptionalAccountId::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_an_account_id()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = OptionalAccountId::some($accountId);

        $this->assertInstanceOf(OptionalAccountId::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $optional = OptionalAccountId::some('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(OptionalAccountId::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_account_id()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = OptionalAccountId::some($accountId);

        $this->assertInstanceOf(AccountId::class, $optional->unwrap());
    }
}
