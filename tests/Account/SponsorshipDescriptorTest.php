<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\SponsorshipDescriptor;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\SponsorshipDescriptor
 */
class SponsorshipDescriptorTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(AccountId::class, SponsorshipDescriptor::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_muxed_account()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = SponsorshipDescriptor::some($accountId);

        $this->assertInstanceOf(SponsorshipDescriptor::class, $optional);
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $optional = SponsorshipDescriptor::some('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(SponsorshipDescriptor::class, $optional);
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_muxed_account()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $optional = SponsorshipDescriptor::some($accountId);

        $this->assertInstanceOf(AccountId::class, $optional->unwrap());
    }
}
