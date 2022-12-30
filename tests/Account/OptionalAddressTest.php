<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\OptionalAddress;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\OptionalAddress
 */
class OptionalAddressTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(AccountId::class, OptionalAddress::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_muxed_account()
    {
        $muxedAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = OptionalAddress::some($muxedAccount);

        $this->assertInstanceOf(OptionalAddress::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $optional = OptionalAddress::some('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(OptionalAddress::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_muxed_account()
    {
        $muxedAccount = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = OptionalAddress::some($muxedAccount);

        $this->assertInstanceOf(AccountId::class, $optional->unwrap());
    }
}
