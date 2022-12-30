<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\PeerStatsList;
use StageRightLabs\Bloom\SCP\TopologyResponseBody;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\TopologyResponseBody
 */
class TopologyResponseBodyTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $buffer = XDR::fresh()->write(new TopologyResponseBody());
        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $topologyResponseBody = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAA==')
            ->read(TopologyResponseBody::class);

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(PeerStatsList::class, $topologyResponseBody->getInboundPeers());
        $this->assertInstanceOf(PeerStatsList::class, $topologyResponseBody->getOutboundPeers());
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalInboundPeers());
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalOutboundPeers());
    }

    /**
     * @test
     * @covers ::withInboundPeers
     * @covers ::getInboundPeers
     */
    public function it_accepts_a_list_of_inbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withInboundPeers(PeerStatsList::empty());

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(PeerStatsList::class, $topologyResponseBody->getInboundPeers());
    }

    /**
     * @test
     * @covers ::withOutboundPeers
     * @covers ::getOutboundPeers
     */
    public function it_accepts_a_list_of_outbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withOutboundPeers(PeerStatsList::empty());

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(PeerStatsList::class, $topologyResponseBody->getOutboundPeers());
    }

    /**
     * @test
     * @covers ::withTotalInboundPeers
     * @covers ::getTotalInboundPeers
     */
    public function it_accepts_a_uint_32_as_a_count_of_inbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withTotalInboundPeers(UInt32::of(1));

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalInboundPeers());
    }

    /**
     * @test
     * @covers ::withTotalInboundPeers
     * @covers ::getTotalInboundPeers
     */
    public function it_accepts_a_native_int_as_a_count_of_inbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withTotalInboundPeers(1);

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalInboundPeers());
    }

    /**
     * @test
     * @covers ::withTotalOutboundPeers
     * @covers ::getTotalOutboundPeers
     */
    public function it_accepts_a_uint_32_as_a_count_of_outbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withTotalOutboundPeers(UInt32::of(2));

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalOutboundPeers());
    }

    /**
     * @test
     * @covers ::withTotalOutboundPeers
     * @covers ::getTotalOutboundPeers
     */
    public function it_accepts_a_native_int_as_a_count_of_outbound_peers()
    {
        $topologyResponseBody = (new TopologyResponseBody())
            ->withTotalOutboundPeers(2);

        $this->assertInstanceOf(TopologyResponseBody::class, $topologyResponseBody);
        $this->assertInstanceOf(UInt32::class, $topologyResponseBody->getTotalOutboundPeers());
    }
}
