<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOpSigner;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\RevokeSponsorshipOpSigner
 */
class RevokeSponsorshipOpSignerTest extends TestCase
{
    /**
     * @test
     * @covers ::fromAddressable
     */
    public function it_can_be_instantiated_from_an_addressable()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $revokeSponsorshipOpSigner = RevokeSponsorshipOpSigner::fromAddressable($accountId);

        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $revokeSponsorshipOpSigner);
        $this->assertInstanceOf(AccountId::class, $revokeSponsorshipOpSigner->getAccountId());
        $this->assertInstanceOf(SignerKey::class, $revokeSponsorshipOpSigner->getSignerKey());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);

        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withAccountId($accountId)
            ->withSignerKey($signerKey);
        $buffer = XDR::fresh()->write($revokeSponsorshipOpSigner);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);

        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withSignerKey($signerKey);
        XDR::fresh()->write($revokeSponsorshipOpSigner);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signer_key_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withAccountId($accountId);
        XDR::fresh()->write($revokeSponsorshipOpSigner);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $revokeSponsorshipOpSigner = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u')
            ->read(RevokeSponsorshipOpSigner::class);

        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $revokeSponsorshipOpSigner);
        $this->assertInstanceOf(AccountId::class, $revokeSponsorshipOpSigner->getAccountId());
        $this->assertInstanceOf(SignerKey::class, $revokeSponsorshipOpSigner->getSignerKey());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withAccountId($accountId);

        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $revokeSponsorshipOpSigner);
        $this->assertInstanceOf(AccountId::class, $revokeSponsorshipOpSigner->getAccountId());
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
        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withSignerKey($signerKey);

        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $revokeSponsorshipOpSigner);
        $this->assertInstanceOf(SignerKey::class, $revokeSponsorshipOpSigner->getSignerKey());
    }
}
