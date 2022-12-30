<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\SignerKeyType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\SignerKeyType
 */
class SignerKeyTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => SignerKeyType::SIGNER_KEY_ED25519,
            1 => SignerKeyType::SIGNER_KEY_PRE_AUTH_TX,
            2 => SignerKeyType::SIGNER_KEY_HASH_X,
            3 => SignerKeyType::SIGNER_KEY_ED25519_SIGNED_PAYLOAD,
        ];
        $memoType = new SignerKeyType();

        $this->assertEquals($expected, $memoType->getOptions());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_the_selected_type()
    {
        $signerType = SignerKeyType::ed25519();
        $this->assertEquals(SignerKeyType::SIGNER_KEY_ED25519, $signerType->getType());
    }

    /**
     * @test
     * @covers ::ed25519
     */
    public function it_can_be_instantiated_as_an_ed25519_signer_key_type()
    {
        $signerType = SignerKeyType::ed25519();

        $this->assertInstanceOf(SignerKeyType::class, $signerType);
        $this->assertEquals(SignerKeyType::SIGNER_KEY_ED25519, $signerType->getType());
    }

    /**
     * @test
     * @covers ::preAuthTx
     */
    public function it_can_be_instantiated_as_a_pre_auth_tx_signer_key_type()
    {
        $signerType = SignerKeyType::preAuthTx();

        $this->assertInstanceOf(SignerKeyType::class, $signerType);
        $this->assertEquals(SignerKeyType::SIGNER_KEY_PRE_AUTH_TX, $signerType->getType());
    }

    /**
     * @test
     * @covers ::hashX
     */
    public function it_can_be_instantiated_as_a_hash_x_signer_key_type()
    {
        $signerType = SignerKeyType::hashX();

        $this->assertInstanceOf(SignerKeyType::class, $signerType);
        $this->assertEquals(SignerKeyType::SIGNER_KEY_HASH_X, $signerType->getType());
    }

    /**
     * @test
     * @covers ::ed25519SignedPayload
     */
    public function it_can_be_instantiated_as_an_ed25519_signed_payload_signer_key_type()
    {
        $signerType = SignerKeyType::ed25519SignedPayload();

        $this->assertInstanceOf(SignerKeyType::class, $signerType);
        $this->assertEquals(SignerKeyType::SIGNER_KEY_ED25519_SIGNED_PAYLOAD, $signerType->getType());
    }
}
