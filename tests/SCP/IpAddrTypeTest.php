<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\IpAddrType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\IpAddrType
 */
class IpAddrTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => IpAddrType::IPV4,
            1 => IpAddrType::IPV6,
        ];
        $ipAddrType = new IpAddrType();

        $this->assertEquals($expected, $ipAddrType->getOptions());
    }

    /**
     * @test
     * @covers ::ipv4
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_ipv4_type()
    {
        $ipAddrType = IpAddrType::ipv4();

        $this->assertInstanceOf(IpAddrType::class, $ipAddrType);
        $this->assertEquals(IpAddrType::IPV4, $ipAddrType->getType());
    }

    /**
     * @test
     * @covers ::ipv6
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_ipv6_type()
    {
        $ipAddrType = IpAddrType::ipv6();

        $this->assertInstanceOf(IpAddrType::class, $ipAddrType);
        $this->assertEquals(IpAddrType::IPV6, $ipAddrType->getType());
    }
}
