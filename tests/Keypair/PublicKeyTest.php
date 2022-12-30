<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Keypair;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Keypair\PublicKey;
use StageRightLabs\Bloom\Keypair\PublicKeyType;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Keypair\PublicKey
 */
class PublicKeyTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_a_discriminator_type()
    {
        $this->assertEquals(PublicKeyType::class, PublicKey::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_has_arms_defined_by_the_stellar_interface_definition_files()
    {
        $publicKey = new PublicKey();
        $expected = [
            'publicKeyTypeEd25519' => ED25519::class,
        ];

        $this->assertEquals($expected, $publicKey->arms());
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_an_address_string()
    {
        $publicKey = PublicKey::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(PublicKey::class, $publicKey);
        $this->assertInstanceOf(ED25519::class, $publicKey->unwrap());
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_an_addressable_object()
    {
        $wallet = new ExampleWallet('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $publicKey = PublicKey::fromAddressable($wallet);

        $this->assertInstanceOf(PublicKey::class, $publicKey);
        $this->assertInstanceOf(ED25519::class, $publicKey->unwrap());
    }

    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_rejects_invalid_addresses()
    {
        $this->expectException(InvalidArgumentException::class);
        PublicKey::fromAddressable('INVALID');
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_can_be_unwrapped()
    {
        $wallet = new ExampleWallet('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $publicKey = PublicKey::fromAddressable($wallet);

        $this->assertInstanceOf(ED25519::class, $publicKey->unwrap());
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_an_address()
    {
        $wallet = new ExampleWallet('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $publicKey = PublicKey::fromAddressable($wallet);

        $this->assertEquals('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW', $publicKey->getAddress());
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_returns_an_empty_string_if_it_does_not_have_an_ed25519()
    {
        $this->assertEmpty((new PublicKey())->getAddress());
    }

    /**
     * @test
     * @covers ::getAccountId
     */
    public function it_returns_an_account_id()
    {
        $publicKey = PublicKey::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(AccountId::class, $publicKey->getAccountId());
    }

    /**
     * @test
     * @covers ::getMuxedAccount
     */
    public function it_returns_a_muxed_account()
    {
        $publicKey = PublicKey::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $this->assertInstanceOf(MuxedAccount::class, $publicKey->getMuxedAccount());
    }

    /**
     * @test
     * @covers ::getWeightedSigner
     */
    public function it_returns_a_weighted_signer()
    {
        $publicKey = PublicKey::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(Signer::class, $publicKey->getWeightedSigner(10));
        $this->assertEquals(10, $publicKey->getWeightedSigner(10)->getWeight()->toNativeInt());
    }
}


class ExampleWallet implements \StageRightLabs\Bloom\Account\Wallet
{
    public function __construct(
        public string $address = '',
        public String $seed = ''
    ) {
        $this->keypair = new \StageRightLabs\Bloom\Keypair\Keypair(
            address: $address,
            seed: $seed
        );
    }

    public function sign(string $message): Signature
    {
        return new Signature();
    }

    public function signDecorated(string $message): DecoratedSignature
    {
        return new DecoratedSignature();
    }

    public function verify(string $signature, string $message): bool
    {
        return true;
    }

    public function getSignatureHint(): SignatureHint
    {
        return SignatureHint::of(substr($this->getAddress(), -4));
    }

    public static function fromAddress(Addressable|string $address): static
    {
        if ($address instanceof Addressable) {
            $address = $address->getAddress();
        }

        return new ExampleWallet(address: $address);
    }

    public static function fromSeed(string $seed): static
    {
        return new ExampleWallet(seed: $seed);
    }

    public function getAddress(): string
    {
        return $this->keypair->getAddress();
    }

    public function getSeed(): string
    {
        return $this->keypair->getSeed();
    }

    public function getAccountId(): \StageRightLabs\Bloom\Account\AccountId
    {
        return $this->keypair->toAccountId();
    }

    public function getMuxedAccount(): MuxedAccount
    {
        return MuxedAccount::fromAddressable($this->address);
    }

    public function canSign(): bool
    {
        return $this->keypair->canSign();
    }

    /**
     * Convert this addressable into a weighted signer for account management.
     *
     * @param UInt32|integer $weight
     * @return Signer
     */
    public function getWeightedSigner(UInt32|int $weight): Signer
    {
        return $this->keypair->getWeightedSigner($weight);
    }
}
