<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\AuthCert;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\AuthCert
 */
class AuthCertTest extends TestCase
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
        $buffer = XDR::fresh()->write($authCert);

        $this->assertEquals('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAARhYmNk', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_public_key_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withExpiration(UInt64::of(1))
            ->withSignature(Signature::of('abcd'));
        XDR::fresh()->write($authCert);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_expiration_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withSignature(Signature::of('abcd'));
        XDR::fresh()->write($authCert);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'))
            ->withExpiration(UInt64::of(1));
        XDR::fresh()->write($authCert);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $authCert = XDR::fromBase64('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQAAAARhYmNk')
            ->read(AuthCert::class);

        $this->assertInstanceOf(AuthCert::class, $authCert);
        $this->assertInstanceOf(Curve25519Public::class, $authCert->getPublicKey());
        $this->assertInstanceOf(UInt64::class, $authCert->getExpiration());
        $this->assertInstanceOf(Signature::class, $authCert->getSignature());
    }

    /**
     * @test
     * @covers ::withPublicKey
     * @covers ::getPublicKey
     */
    public function it_accepts_a_public_key()
    {
        $authCert = (new AuthCert())
            ->withPublicKey(Curve25519Public::of('example'));

        $this->assertInstanceOf(AuthCert::class, $authCert);
        $this->assertInstanceOf(Curve25519Public::class, $authCert->getPublicKey());
    }

    /**
     * @test
     * @covers ::withExpiration
     * @covers ::getExpiration
     */
    public function it_accepts_an_expiration()
    {
        $authCert = (new AuthCert())->withExpiration(UInt64::of(1));

        $this->assertInstanceOf(AuthCert::class, $authCert);
        $this->assertInstanceOf(UInt64::class, $authCert->getExpiration());
    }

    /**
     * @test
     * @covers ::withSignature
     * @covers ::getSignature
     */
    public function it_accepts_a_signature()
    {
        $authCert = (new AuthCert())->withSignature(Signature::of('abcd'));

        $this->assertInstanceOf(AuthCert::class, $authCert);
        $this->assertInstanceOf(Signature::class, $authCert->getSignature());
    }
}
