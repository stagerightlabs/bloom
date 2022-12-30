<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\IpAddrType;
use StageRightLabs\Bloom\SCP\IPv4;
use StageRightLabs\Bloom\SCP\IPv6;
use StageRightLabs\Bloom\SCP\PeerAddressIp;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\PeerAddressIp
 */
class PeerAddressIpTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(IpAddrType::class, PeerAddressIp::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            IpAddrType::IPV4 => IPv4::class,
            IpAddrType::IPV6 => IPv6::class,
        ];

        $this->assertEquals($expected, PeerAddressIp::arms());
    }

    /**
     * @test
     * @covers ::wrapIPv4
     * @covers ::unwrap
     */
    public function it_can_wrap_an_ipv4()
    {
        $ipv4 = IPv4::of('1.1.1.1');
        $peerAddressIp = PeerAddressIp::wrapIPv4($ipv4);

        $this->assertInstanceOf(PeerAddressIp::class, $peerAddressIp);
        $this->assertInstanceOf(IPv4::class, $peerAddressIp->unwrap());
    }

    /**
     * @test
     * @covers ::wrapIPv6
     * @covers ::unwrap
     */
    public function it_can_wrap_an_ipv6()
    {
        $ipv6 = IPv6::of('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $peerAddressIp = PeerAddressIp::wrapIPv6($ipv6);

        $this->assertInstanceOf(PeerAddressIp::class, $peerAddressIp);
        $this->assertInstanceOf(IPv6::class, $peerAddressIp->unwrap());
    }
}
