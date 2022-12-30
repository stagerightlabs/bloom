<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKeyEd25519SignedPayload;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\SignerKeyEd25519SignedPayload
 */
class SignerKeyEd25519SignedPayloadTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_from_a_ed25519_signature_and_a_payload()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $payload = 'payload';
        $signerKeyEd25119SignedPayload = SignerKeyEd25519SignedPayload::of($ed25519, $payload);

        $this->assertInstanceOf(SignerKeyEd25519SignedPayload::class, $signerKeyEd25119SignedPayload);
        $this->assertInstanceOf(ED25519::class, $signerKeyEd25119SignedPayload->getEd25519());
        $this->assertEquals('payload', $signerKeyEd25119SignedPayload->getPayload());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $payload = 'payload';
        $signerKeyEd25119SignedPayload = SignerKeyEd25519SignedPayload::of($ed25519, $payload);

        $buffer = XDR::fresh()->write($signerKeyEd25119SignedPayload);

        $this->assertEquals('am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAHcGF5bG9hZAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $payload = '';
        $signerKeyEd25119SignedPayload = (new SignerKeyEd25519SignedPayload())
            ->withPayload($payload);
        XDR::fresh()->write($signerKeyEd25119SignedPayload);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_payload_is_required_for_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKeyEd25119SignedPayload = (new SignerKeyEd25519SignedPayload())
            ->withEd25519($ed25519);
        XDR::fresh()->write($signerKeyEd25119SignedPayload);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $signerKeyEd25119SignedPayload = XDR::fromBase64('am1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAHcGF5bG9hZAA=')
            ->read(SignerKeyEd25519SignedPayload::class);

        $this->assertInstanceOf(SignerKeyEd25519SignedPayload::class, $signerKeyEd25119SignedPayload);
        $this->assertEquals('payload', $signerKeyEd25119SignedPayload->getPayload());
    }

    /**
     * @test
     * @covers ::getEd25519
     * @covers ::withEd25519
     */
    public function it_accepts_an_ed25519_signature()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKeyEd25119SignedPayload = (new SignerKeyEd25519SignedPayload())->withEd25519($ed25519);

        $this->assertInstanceOf(ED25519::class, $signerKeyEd25119SignedPayload->getEd25519());
    }

    /**
     * @test
     * @covers ::getPayload
     * @covers ::withPayload
     */
    public function it_accepts_a_payload()
    {
        $payload = 'payload';
        $signerKeyEd25119SignedPayload = (new SignerKeyEd25519SignedPayload())->withPayload($payload);

        $this->assertEquals($payload, $signerKeyEd25119SignedPayload->getPayload());
    }

    /**
     * @test
     * @covers ::withPayload
     */
    public function it_does_not_accept_a_value_longer_than_64_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new SignerKeyEd25519SignedPayload())->withPayload(str_repeat('A', 65));
    }
}
