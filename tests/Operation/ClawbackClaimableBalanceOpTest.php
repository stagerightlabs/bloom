<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClawbackClaimableBalanceOp
 */
class ClawbackClaimableBalanceOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ClawbackClaimableBalanceOp::operation(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $clawbackClaimableBalanceOp = new ClawbackClaimableBalanceOp();
        $this->assertFalse($clawbackClaimableBalanceOp->isReady());

        $clawbackClaimableBalanceOp = $clawbackClaimableBalanceOp->withBalanceId('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e');
        $this->assertTrue($clawbackClaimableBalanceOp->isReady());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_MEDIUM, (new ClawbackClaimableBalanceOp())->getThreshold());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $clawbackClaimableBalanceOp = (new ClawbackClaimableBalanceOp())
            ->withBalanceId($claimableBalanceId);
        $buffer = XDR::fresh()->write($clawbackClaimableBalanceOp);

        $this->assertEquals('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_balance_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ClawbackClaimableBalanceOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $clawbackClaimableBalanceOp = XDR::fromBase64('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL')
            ->read(ClawbackClaimableBalanceOp::class);

        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $clawbackClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $clawbackClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $clawbackClaimableBalanceOp = (new ClawbackClaimableBalanceOp())
            ->withBalanceId($claimableBalanceId);

        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $clawbackClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $clawbackClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id_string()
    {
        $clawbackClaimableBalanceOp = (new ClawbackClaimableBalanceOp())
            ->withBalanceId('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e');

        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $clawbackClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $clawbackClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id_hash()
    {
        $clawbackClaimableBalanceOp = (new ClawbackClaimableBalanceOp())
            ->withBalanceId(Hash::fromHex('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'));

        $this->assertInstanceOf(ClawbackClaimableBalanceOp::class, $clawbackClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $clawbackClaimableBalanceOp->getBalanceId());
    }
}
