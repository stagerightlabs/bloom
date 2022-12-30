<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\PeerStats;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\PeerStats
 */
class PeerStatsTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        $buffer = XDR::fresh()->write($peerStats);

        $this->assertEquals(
            'AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAB2V4YW1wbGUAAAAAAAAAAAEAAAAAAAAAAgAAAAAAAAADAAAAAAAAAAQAAAAAAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAAAAAACAAAAAAAAAAJAAAAAAAAAAoAAAAAAAAACwAAAAAAAAAMAAAAAAAAAA0=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_version_str_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_messages_read_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_messages_written_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_bytes_read_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_bytes_written_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_seconds_connected_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_unique_flood_bytes_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_duplicate_flood_bytes_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_unique_fetch_bytes_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_duplicate_fetch_bytes_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_unique_flood_messages_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_duplicate_flood_messages_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withUniqueFetchMessagesReceived(UInt64::of(12))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_unique_fetch_messages_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_duplicate_fetch_messages_received_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withVersionStr(String100::of('example'))
            ->withMessagesRead(UInt64::of(1))
            ->withMessagesWritten(UInt64::of(2))
            ->withBytesRead(UInt64::of(3))
            ->withBytesWritten(UInt64::of(4))
            ->withSecondsConnected(UInt64::of(5))
            ->withUniqueFloodBytesReceived(UInt64::of(6))
            ->withDuplicateFloodBytesReceived(UInt64::of(7))
            ->withUniqueFetchBytesReceived(UInt64::of(8))
            ->withDuplicateFetchBytesReceived(UInt64::of(9))
            ->withUniqueFloodMessagesReceived(UInt64::of(10))
            ->withDuplicateFloodMessagesReceived(UInt64::of(11))
            ->withUniqueFetchMessagesReceived(UInt64::of(12));
        XDR::fresh()->write($peerStats);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $peerStats = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAB2V4YW1wbGUAAAAAAAAAAAEAAAAAAAAAAgAAAAAAAAADAAAAAAAAAAQAAAAAAAAABQAAAAAAAAAGAAAAAAAAAAcAAAAAAAAACAAAAAAAAAAJAAAAAAAAAAoAAAAAAAAACwAAAAAAAAAMAAAAAAAAAA0=')
            ->read(PeerStats::class);

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(NodeId::class, $peerStats->getId());
        $this->assertInstanceOf(String100::class, $peerStats->getVersionStr());
        $this->assertInstanceOf(UInt64::class, $peerStats->getMessagesRead());
        $this->assertInstanceOf(UInt64::class, $peerStats->getMessagesWritten());
        $this->assertInstanceOf(UInt64::class, $peerStats->getBytesRead());
        $this->assertInstanceOf(UInt64::class, $peerStats->getBytesWritten());
        $this->assertInstanceOf(UInt64::class, $peerStats->getSecondsConnected());
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFloodBytesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFloodBytesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFetchBytesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFetchBytesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFloodMessagesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFloodMessagesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFetchMessagesReceived());
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFetchMessagesReceived());
    }

    /**
     * @test
     * @covers ::withId
     * @covers ::getId
     */
    public function it_accepts_an_id()
    {
        $peerStats = (new PeerStats())
            ->withId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(NodeId::class, $peerStats->getId());
    }

    /**
     * @test
     * @covers ::withVersionStr
     * @covers ::getVersionStr
     */
    public function it_accepts_a_string_100_as_a_version_str()
    {
        $peerStats = (new PeerStats())
            ->withVersionStr(String100::of('example'));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(String100::class, $peerStats->getVersionStr());
    }

    /**
     * @test
     * @covers ::withVersionStr
     * @covers ::getVersionStr
     */
    public function it_accepts_a_native_string_as_a_version_str()
    {
        $peerStats = (new PeerStats())
            ->withVersionStr('example');

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(String100::class, $peerStats->getVersionStr());
    }

    /**
     * @test
     * @covers ::withMessagesRead
     * @covers ::getMessagesRead
     */
    public function it_accepts_a_messages_read_count()
    {
        $peerStats = (new PeerStats())
            ->withMessagesRead(UInt64::of(1));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getMessagesRead());
    }

    /**
     * @test
     * @covers ::withMessagesWritten
     * @covers ::getMessagesWritten
     */
    public function it_accepts_a_messages_written_count()
    {
        $peerStats = (new PeerStats())
            ->withMessagesWritten(UInt64::of(2));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getMessagesWritten());
    }

    /**
     * @test
     * @covers ::withBytesRead
     * @covers ::getBytesRead
     */
    public function it_accepts_a_bytes_read_count()
    {
        $peerStats = (new PeerStats())
            ->withBytesRead(UInt64::of(3));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getBytesRead());
    }

    /**
     * @test
     * @covers ::withBytesWritten
     * @covers ::getBytesWritten
     */
    public function it_accepts_a_bytes_written_count()
    {
        $peerStats = (new PeerStats())
            ->withBytesWritten(UInt64::of(4));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getBytesWritten());
    }

    /**
     * @test
     * @covers ::withSecondsConnected
     * @covers ::getSecondsConnected
     */
    public function it_accepts_a_seconds_connected_count()
    {
        $peerStats = (new PeerStats())
            ->withSecondsConnected(UInt64::of(5));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getSecondsConnected());
    }

    /**
     * @test
     * @covers ::withUniqueFloodBytesReceived
     * @covers ::getUniqueFloodBytesReceived
     */
    public function it_accepts_a_unique_flood_bytes_received_count()
    {
        $peerStats = (new PeerStats())
            ->withUniqueFloodBytesReceived(UInt64::of(6));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFloodBytesReceived());
    }

    /**
     * @test
     * @covers ::withDuplicateFloodBytesReceived
     * @covers ::getDuplicateFloodBytesReceived
     */
    public function it_accepts_a_duplicate_flood_bytes_received_count()
    {
        $peerStats = (new PeerStats())
            ->withDuplicateFloodBytesReceived(UInt64::of(7));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFloodBytesReceived());
    }

    /**
     * @test
     * @covers ::withUniqueFetchBytesReceived
     * @covers ::getUniqueFetchBytesReceived
     */
    public function it_accepts_a_unique_fetch_bytes_received_count()
    {
        $peerStats = (new PeerStats())
            ->withUniqueFetchBytesReceived(UInt64::of(8));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFetchBytesReceived());
    }

    /**
     * @test
     * @covers ::withDuplicateFetchBytesReceived
     * @covers ::getDuplicateFetchBytesReceived
     */
    public function it_accepts_a_duplicate_fetch_bytes_received_count()
    {
        $peerStats = (new PeerStats())
            ->withDuplicateFetchBytesReceived(UInt64::of(9));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFetchBytesReceived());
    }

    /**
     * @test
     * @covers ::withUniqueFloodMessagesReceived
     * @covers ::getUniqueFloodMessagesReceived
     */
    public function it_accepts_a_unique_flood_messages_received_count()
    {
        $peerStats = (new PeerStats())
            ->withUniqueFloodMessagesReceived(UInt64::of(10));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFloodMessagesReceived());
    }

    /**
     * @test
     * @covers ::withDuplicateFloodMessagesReceived
     * @covers ::getDuplicateFloodMessagesReceived
     */
    public function it_accepts_a_duplicate_flood_messages_received_count()
    {
        $peerStats = (new PeerStats())
            ->withDuplicateFloodMessagesReceived(UInt64::of(11));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFloodMessagesReceived());
    }

    /**
     * @test
     * @covers ::withUniqueFetchMessagesReceived
     * @covers ::getUniqueFetchMessagesReceived
     */
    public function it_accepts_a_unique_fetch_messages_received_count()
    {
        $peerStats = (new PeerStats())
            ->withUniqueFetchMessagesReceived(UInt64::of(12));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getUniqueFetchMessagesReceived());
    }

    /**
     * @test
     * @covers ::withDuplicateFetchMessagesReceived
     * @covers ::getDuplicateFetchMessagesReceived
     */
    public function it_accepts_a_duplicate_fetch_messages_received_count()
    {
        $peerStats = (new PeerStats())
            ->withDuplicateFetchMessagesReceived(UInt64::of(13));

        $this->assertInstanceOf(PeerStats::class, $peerStats);
        $this->assertInstanceOf(UInt64::class, $peerStats->getDuplicateFetchMessagesReceived());
    }
}
