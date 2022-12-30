<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\AuthCert;
use StageRightLabs\Bloom\SCP\Hello;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\Hello
 */
class HelloTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        $buffer = XDR::fresh()->write($hello);

        $this->assertEquals(
            'AAAAAQAAAAIAAAADSyJ3d9TdH8Ycb4hPSGQdArTRIdP9Moywi1Ux/Kzav4oAAAABNQAAAAAAAAYAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne5leGFtcGxlAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAABGFiY2QAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANw==',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_version_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_overlay_version_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_overlay_min_version_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_network_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_version_string_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_listening_port_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_peer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withCert($authCert)
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_cert_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withNonce(UInt256::of('7'));
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_nonce_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())
            ->withLedgerVersion(UInt32::of(1))
            ->withOverlayVersion(UInt32::of(2))
            ->withOverlayMinVersion(UInt32::of(3))
            ->withNetworkId(Hash::make('4'))
            ->withVersionString(String100::of('5'))
            ->withListeningPort(6)
            ->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withCert($authCert);
        XDR::fresh()->write($hello);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hello = XDR::fromBase64('AAAAAQAAAAIAAAADSyJ3d9TdH8Ycb4hPSGQdArTRIdP9Moywi1Ux/Kzav4oAAAABNQAAAAAAAAYAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne5leGFtcGxlAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAAABGFiY2QAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANw==')
            ->read(Hello::class);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getLedgerVersion());
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayVersion());
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayMinVersion());
        $this->assertInstanceOf(Hash::class, $hello->getNetworkId());
        $this->assertInstanceOf(String100::class, $hello->getVersionString());
        $this->assertTrue(is_int($hello->getListeningPort()));
        $this->assertInstanceOf(NodeId::class, $hello->getPeerId());
        $this->assertInstanceOf(AuthCert::class, $hello->getCert());
        $this->assertInstanceOf(UInt256::class, $hello->getNonce());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_uint32_as_a_ledger_version()
    {
        $hello = (new Hello())->withLedgerVersion(UInt32::of(1));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withLedgerVersion
     * @covers ::getLedgerVersion
     */
    public function it_accepts_a_native_int_as_a_ledger_version()
    {
        $hello = (new Hello())->withLedgerVersion(1);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getLedgerVersion());
    }

    /**
     * @test
     * @covers ::withOverlayVersion
     * @covers ::getOverlayVersion
     */
    public function it_accepts_a_uint32_as_an_overlay_version()
    {
        $hello = (new Hello())->withOverlayVersion(UInt32::of(2));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayVersion());
    }

    /**
     * @test
     * @covers ::withOverlayVersion
     * @covers ::getOverlayVersion
     */
    public function it_accepts_a_native_int_as_an_overlay_version()
    {
        $hello = (new Hello())->withOverlayVersion(2);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayVersion());
    }

    /**
     * @test
     * @covers ::withOverlayMinVersion
     * @covers ::getOverlayMinVersion
     */
    public function it_accepts_a_uint32_as_an_overlay_min_version()
    {
        $hello = (new Hello())->withOverlayMinVersion(UInt32::of(3));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayMinVersion());
    }

    /**
     * @test
     * @covers ::withOverlayMinVersion
     * @covers ::getOverlayMinVersion
     */
    public function it_accepts_a_native_int_as_an_overlay_min_version()
    {
        $hello = (new Hello())->withOverlayMinVersion(3);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt32::class, $hello->getOverlayMinVersion());
    }

    /**
     * @test
     * @covers ::withNetworkId
     * @covers ::getNetworkId
     */
    public function it_accepts_a_network_id()
    {
        $hello = (new Hello())->withNetworkId(Hash::make('4'));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(Hash::class, $hello->getNetworkId());
    }

    /**
     * @test
     * @covers ::withVersionString
     * @covers ::getVersionString
     */
    public function it_accepts_a_string100_as_a_version_string()
    {
        $hello = (new Hello())->withVersionString(String100::of('5'));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(String100::class, $hello->getVersionString());
    }

    /**
     * @test
     * @covers ::withVersionString
     * @covers ::getVersionString
     */
    public function it_accepts_a_native_string_as_a_version_string()
    {
        $hello = (new Hello())->withVersionString('5');

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(String100::class, $hello->getVersionString());
    }

    /**
     * @test
     * @covers ::withListeningPort
     * @covers ::getListeningPort
     */
    public function it_accepts_a_listening_port()
    {
        $hello = (new Hello())->withListeningPort(6);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertTrue(is_int($hello->getListeningPort()));
    }

    /**
     * @test
     * @covers ::withPeerId
     * @covers ::getPeerId
     */
    public function it_accepts_a_peer_id()
    {
        $hello = (new Hello())->withPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(NodeId::class, $hello->getPeerId());
    }

    /**
     * @test
     * @covers ::withCert
     * @covers ::getCert
     */
    public function it_accepts_an_auth_cert()
    {
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        $hello = (new Hello())->withCert($authCert);

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(AuthCert::class, $hello->getCert());
    }

    /**
     * @test
     * @covers ::withNonce
     * @covers ::getNonce
     */
    public function it_accepts_a_nonce()
    {
        $hello = (new Hello())->withNonce(UInt256::of('7'));

        $this->assertInstanceOf(Hello::class, $hello);
        $this->assertInstanceOf(UInt256::class, $hello->getNonce());
    }
}
