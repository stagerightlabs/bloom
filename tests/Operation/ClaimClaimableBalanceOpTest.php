<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Operation;

use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Operation\ClaimClaimableBalanceOp;
use StageRightLabs\Bloom\Operation\Operation;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Operation\ClaimClaimableBalanceOp
 */
class ClaimClaimableBalanceOpTest extends TestCase
{
    /**
     * @test
     * @covers ::operation
     */
    public function it_can_create_an_operation()
    {
        $operation = ClaimClaimableBalanceOp::operation(
            balanceId: '0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'
        );

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $operation->getBody()->unwrap());
    }

    /**
     * @test
     * @covers ::isReady
     */
    public function it_knows_when_it_is_ready()
    {
        $claimClaimableBalanceOp = new ClaimClaimableBalanceOp();
        $this->assertFalse($claimClaimableBalanceOp->isReady());

        $claimClaimableBalanceOp = $claimClaimableBalanceOp->withBalanceId('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e');
        $this->assertTrue($claimClaimableBalanceOp->isReady());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimClaimableBalanceOp = (new ClaimClaimableBalanceOp())
            ->withBalanceId($claimableBalanceId);
        $buffer = XDR::fresh()->write($claimClaimableBalanceOp);

        $this->assertEquals('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_balance_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new ClaimClaimableBalanceOp());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimClaimableBalanceOp = XDR::fromBase64('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL')
            ->read(ClaimClaimableBalanceOp::class);

        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $claimClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id()
    {
        $claimableBalanceId = (new ClaimableBalanceId())->wrapHash(Hash::make('1'));
        $claimClaimableBalanceOp = (new ClaimClaimableBalanceOp())
            ->withBalanceId($claimableBalanceId);

        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $claimClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id_string()
    {
        $claimClaimableBalanceOp = (new ClaimClaimableBalanceOp())
            ->withBalanceId('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e');

        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $claimClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::withBalanceId
     * @covers ::getBalanceId
     */
    public function it_accepts_a_balance_id_hash()
    {
        $claimClaimableBalanceOp = (new ClaimClaimableBalanceOp())
            ->withBalanceId(Hash::fromHex('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'));

        $this->assertInstanceOf(ClaimClaimableBalanceOp::class, $claimClaimableBalanceOp);
        $this->assertInstanceOf(ClaimableBalanceId::class, $claimClaimableBalanceOp->getBalanceId());
    }

    /**
     * @test
     * @covers ::getThreshold
     */
    public function it_has_a_threshold_category()
    {
        $this->assertEquals(Thresholds::CATEGORY_LOW, (new ClaimClaimableBalanceOp())->getThreshold());
    }
}
