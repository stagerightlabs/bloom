<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\IPv4;
use StageRightLabs\Bloom\SCP\PeerAddress;
use StageRightLabs\Bloom\SCP\PeerAddressIp;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\PeerAddress
 */
class PeerAddressTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ipv4 = IPv4::of('1.1.1.1');
        $peerAddressIp = PeerAddressIp::wrapIPv4($ipv4);
        $peerAddress = (new PeerAddress())
            ->withIp($peerAddressIp)
            ->withPort(8000)
            ->withNumFailures(1);
        $buffer = XDR::fresh()->write($peerAddress);

        $this->assertEquals('AAAAAAEBAQEAAB9AAAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_peer_address_ip_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerAddress = (new PeerAddress())
            ->withPort(8000)
            ->withNumFailures(1);
        XDR::fresh()->write($peerAddress);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_port_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ipv4 = IPv4::of('1.1.1.1');
        $peerAddressIp = PeerAddressIp::wrapIPv4($ipv4);
        $peerAddress = (new PeerAddress())
            ->withIp($peerAddressIp)
            ->withNumFailures(1);
        XDR::fresh()->write($peerAddress);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_failures_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ipv4 = IPv4::of('1.1.1.1');
        $peerAddressIp = PeerAddressIp::wrapIPv4($ipv4);
        $peerAddress = (new PeerAddress())
            ->withIp($peerAddressIp)
            ->withPort(8000);
        XDR::fresh()->write($peerAddress);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $peerAddress = XDR::fromBase64('AAAAAAEBAQEAAB9AAAAAAQ==')
            ->read(PeerAddress::class);

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(PeerAddressIp::class, $peerAddress->getIp());
        $this->assertInstanceOf(UInt32::class, $peerAddress->getPort());
        $this->assertInstanceOf(UInt32::class, $peerAddress->getNumFailures());
    }

    /**
     * @test
     * @covers ::withIp
     * @covers ::getIp
     */
    public function it_accepts_an_ip()
    {
        $ipv4 = IPv4::of('1.1.1.1');
        $peerAddressIp = PeerAddressIp::wrapIPv4($ipv4);
        $peerAddress = (new PeerAddress())
            ->withIp($peerAddressIp);

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(PeerAddressIp::class, $peerAddress->getIp());
    }

    /**
     * @test
     * @covers ::withPort
     * @covers ::getPort
     */
    public function it_accepts_a_uint32_as_a_port()
    {
        $peerAddress = (new PeerAddress())->withPort(UInt32::of(8000));

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(UInt32::class, $peerAddress->getPort());
    }

    /**
     * @test
     * @covers ::withPort
     * @covers ::getPort
     */
    public function it_accepts_a_native_int_as_a_port()
    {
        $peerAddress = (new PeerAddress())->withPort(8000);

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(UInt32::class, $peerAddress->getPort());
    }

    /**
     * @test
     * @covers ::withNumFailures
     * @covers ::getNumFailures
     */
    public function it_accepts_a_uint32_as_a_failure_count()
    {
        $peerAddress = (new PeerAddress())->withNumFailures(UInt32::of(1));

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(UInt32::class, $peerAddress->getNumFailures());
    }

    /**
     * @test
     * @covers ::withNumFailures
     * @covers ::getNumFailures
     */
    public function it_accepts_a_native_int_as_a_failure_count()
    {
        $peerAddress = (new PeerAddress())->withNumFailures(1);

        $this->assertInstanceOf(PeerAddress::class, $peerAddress);
        $this->assertInstanceOf(UInt32::class, $peerAddress->getNumFailures());
    }
}
