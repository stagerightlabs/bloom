<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\ClaimableBalance;

use StageRightLabs\Bloom\Account\Account;
use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantV0;
use StageRightLabs\Bloom\ClaimableBalance\ClaimPredicate;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\ClaimableBalance\ClaimantV0
 */
class ClaimantV0Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountId = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $predicate = ClaimPredicate::unconditional();
        $claimantV0 = (new ClaimantV0())
            ->withAccountId($accountId)
            ->withPredicate($predicate);
        $buffer = XDR::fresh()->write($claimantV0);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $predicate = ClaimPredicate::unconditional();
        $claimantV0 = (new ClaimantV0())
            ->withPredicate($predicate);
        XDR::fresh()->write($claimantV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_predicate_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $accountId = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $claimantV0 = (new ClaimantV0())
            ->withAccountId($accountId);
        XDR::fresh()->write($claimantV0);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimantV0 = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAA==')
            ->read(ClaimantV0::class);

        $this->assertInstanceOf(ClaimantV0::class, $claimantV0);
        $this->assertInstanceOf(AccountId::class, $claimantV0->getAccountId());
        $this->assertInstanceOf(ClaimPredicate::class, $claimantV0->getPredicate());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $accountId = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $claimantV0 = (new ClaimantV0())->withAccountId($accountId);

        $this->assertInstanceOf(ClaimantV0::class, $claimantV0);
        $this->assertInstanceOf(AccountId::class, $claimantV0->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_addressable_as_an_account_id()
    {
        $accountId = Account::fromAddress('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $claimantV0 = (new ClaimantV0())->withAccountId($accountId);

        $this->assertInstanceOf(ClaimantV0::class, $claimantV0);
        $this->assertInstanceOf(AccountId::class, $claimantV0->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_a_string_as_an_account_id()
    {
        $claimantV0 = (new ClaimantV0())->withAccountId('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');

        $this->assertInstanceOf(ClaimantV0::class, $claimantV0);
        $this->assertInstanceOf(AccountId::class, $claimantV0->getAccountId());
    }

    /**
     * @test
     * @covers ::withPredicate
     * @covers ::getPredicate
     */
    public function it_accepts_a_claim_predicate()
    {
        $predicate = ClaimPredicate::unconditional();
        $claimantV0 = (new ClaimantV0())->withPredicate($predicate);

        $this->assertInstanceOf(ClaimantV0::class, $claimantV0);
        $this->assertInstanceOf(ClaimPredicate::class, $claimantV0->getPredicate());
    }
}
