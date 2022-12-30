<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Account\Signer
 */
class SignerTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_statically()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signerA = Signer::of($signerKey, 1);
        $signerB = Signer::of($signerKey, UInt32::of(1));

        $this->assertInstanceOf(Signer::class, $signerA);
        $this->assertInstanceOf(Signer::class, $signerB);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signer = Signer::of($signerKey, 1);
        $buffer = XDR::fresh()->write($signer);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signer_key_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $signer = (new Signer())->withWeight(1);
        XDR::fresh()->write($signer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_weight_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signer = (new Signer())->withSignerKey($signerKey);
        XDR::fresh()->write($signer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $signer = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAQ==')
            ->read(Signer::class);

        $this->assertInstanceOf(Signer::class, $signer);
        $this->assertInstanceOf(SignerKey::class, $signer->getSignerKey());
        $this->assertInstanceOf(UInt32::class, $signer->getWeight());
    }

    /**
     * @test
     * @covers ::withSignerKey
     * @covers ::getSignerKey
     */
    public function it_accepts_a_signer_key()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $signer = (new Signer())->withSignerKey($signerKey);

        $this->assertInstanceOf(SignerKey::class, $signer->getSignerKey());
    }

    /**
     * @test
     * @covers ::withWeight
     * @covers ::getWeight
     */
    public function it_accepts_a_weight()
    {
        $signerA = (new Signer())->withWeight(1);
        $signerB = (new Signer())->withWeight(UInt32::of(1));

        $this->assertInstanceOf(UInt32::class, $signerA->getWeight());
        $this->assertInstanceOf(UInt32::class, $signerB->getWeight());
    }
}
