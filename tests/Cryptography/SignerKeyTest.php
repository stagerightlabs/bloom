<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Cryptography\SignerKeyEd25519SignedPayload;
use StageRightLabs\Bloom\Cryptography\SignerKeyType;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\SignerKey
 */
class SignerKeyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(SignerKeyType::class, SignerKey::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            SignerKeyType::SIGNER_KEY_ED25519                => ED25519::class,
            SignerKeyType::SIGNER_KEY_PRE_AUTH_TX            => UInt256::class,
            SignerKeyType::SIGNER_KEY_HASH_X                 => UInt256::class,
            SignerKeyType::SIGNER_KEY_ED25519_SIGNED_PAYLOAD => SignerKeyEd25519SignedPayload::class,
        ];

        $this->assertEquals($expected, SignerKey::arms());
    }

    /**
     * @test
     * @covers ::wrapEd25519
     */
    public function it_can_be_instantiated_from_an_ed25519_signature()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);

        $this->assertInstanceOf(SignerKey::class, $signerKey);
        $this->assertInstanceOf(ED25519::class, $signerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapPreAuthTx
     */
    public function it_can_be_instantiated_from_a_pre_auth_tx()
    {
        $preAuthTx = UInt256::of('pre auth tx');
        $signerKey = SignerKey::wrapPreAuthTx($preAuthTx);

        $this->assertInstanceOf(SignerKey::class, $signerKey);
        $this->assertInstanceOf(UInt256::class, $signerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapHashX
     */
    public function it_can_be_instantiated_from_a_hash_x()
    {
        $hashX = UInt256::of('hash x');
        $signerKey = SignerKey::wrapHashX($hashX);

        $this->assertInstanceOf(SignerKey::class, $signerKey);
        $this->assertInstanceOf(UInt256::class, $signerKey->unwrap());
    }

    /**
     * @test
     * @covers ::wrapEd25519SignedPayload
     */
    public function it_can_be_instantiated_from_an_ed25519_signed_payload()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $payload = 'payload';
        $signerKeyEd25119SignedPayload = SignerKeyEd25519SignedPayload::of($ed25519, $payload);
        $signerKey = SignerKey::wrapEd25519SignedPayload($signerKeyEd25119SignedPayload);

        $this->assertInstanceOf(SignerKey::class, $signerKey);
        $this->assertInstanceOf(ED25519::class, $signerKey->unwrap()->getEd25519());
        $this->assertEquals($payload, $signerKey->unwrap()->getPayload());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_can_be_unwrapped()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);

        $this->assertInstanceOf(ED25519::class, $signerKey->unwrap());
    }
}
