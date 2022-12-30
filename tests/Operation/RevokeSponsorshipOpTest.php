<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Cryptography\ED25519;
use StageRightLabs\Bloom\Cryptography\SignerKey;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Ledger\LedgerKeyAccount;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOp;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipOpSigner;
use StageRightLabs\Bloom\Operation\RevokeSponsorshipType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\RevokeSponsorshipOp
 */
class RevokeSponsorshipOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation_from_a_ledger_key()
    {
        $operation = RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::account('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(LedgerKey::class, $operation->getBody()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation_from_a_revoke_sponsorship_op_signer()
    {
        $operation = RevokeSponsorshipOp::operation(
            signer: RevokeSponsorshipOpSigner::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(RevokeSponsorshipOp::class, $operation->getBody()->unwrap());
        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $operation->getBody()->unwrap()->unwrap());
    }

    /**
     * @test
     * @covers ::operation
     */
    public function it_requires_either_a_ledger_key_or_signer()
    {
        $this->expectException(InvalidArgumentException::class);
        RevokeSponsorshipOp::operation();
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $revokeSponsorshipOp = new RevokeSponsorshipOp();
        $this->assertFalse($revokeSponsorshipOp->isReady());

        $revokeSponsorshipOp = $revokeSponsorshipOp->wrapLedgerKey(
            LedgerKey::account('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW')
        );
        $this->assertTrue($revokeSponsorshipOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new RevokeSponsorshipOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(RevokeSponsorshipType::class, RevokeSponsorshipOp::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            RevokeSponsorshipType::REVOKE_SPONSORSHIP_LEDGER_ENTRY => LedgerKey::class,
            RevokeSponsorshipType::REVOKE_SPONSORSHIP_SIGNER       => RevokeSponsorshipOpSigner::class,
        ];

        $this->assertEquals($expected, RevokeSponsorshipOp::arms());
    }


    /**
     * @test
     * @covers ::wrapLedgerKey
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_key()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ledgerKeyAccount = (new LedgerKeyAccount())->withAccountId($accountId);
        $ledgerKey = LedgerKey::wrapLedgerKeyAccount($ledgerKeyAccount);
        $revokeSponsorshipOp = RevokeSponsorshipOp::wrapLedgerKey($ledgerKey);

        $this->assertInstanceOf(RevokeSponsorshipOp::class, $revokeSponsorshipOp);
        $this->assertInstanceOf(LedgerKey::class, $revokeSponsorshipOp->unwrap());
    }

    /**
     * @test
     * @covers ::wrapRevokeSponsorshipOpSigner
     * @covers ::unwrap
     */
    public function it_can_wrap_a_revoke_sponsorship_op_signer()
    {
        $ed25519 = ED25519::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $signerKey = SignerKey::wrapEd25519($ed25519);
        $revokeSponsorshipOpSigner = (new RevokeSponsorshipOpSigner())
            ->withSignerKey($signerKey);
        $revokeSponsorshipOp = RevokeSponsorshipOp::wrapRevokeSponsorshipOpSigner($revokeSponsorshipOpSigner);

        $this->assertInstanceOf(RevokeSponsorshipOp::class, $revokeSponsorshipOp);
        $this->assertInstanceOf(RevokeSponsorshipOpSigner::class, $revokeSponsorshipOp->unwrap());
    }
}
