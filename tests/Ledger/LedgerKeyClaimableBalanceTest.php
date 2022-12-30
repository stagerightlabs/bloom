<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyClaimableBalance;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyClaimableBalance
 */
class LedgerKeyClaimableBalanceTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId(ClaimableBalanceId::wrapHash(Hash::make('1')));
        $buffer = XDR::fresh()->write($ledgerKeyClaimableBalance);

        $this->assertEquals('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_claimable_balance_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new LedgerKeyClaimableBalance());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyClaimableBalance = XDR::fromBase64('AAAAAGuGsnP/NPzhnWuATv9aP1dHraTqoi8dScAeUt23h1tL')
            ->read(LedgerKeyClaimableBalance::class);

        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKeyClaimableBalance);
        $this->assertInstanceOf(ClaimableBalanceId::class, $ledgerKeyClaimableBalance->getClaimableBalanceId());
    }

    /**
     * @test
     * @covers ::withClaimableBalanceId
     * @covers ::getClaimableBalanceId
     */
    public function it_accepts_a_claimable_balance_id()
    {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId(ClaimableBalanceId::wrapHash(Hash::make('1')));

        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKeyClaimableBalance);
        $this->assertInstanceOf(ClaimableBalanceId::class, $ledgerKeyClaimableBalance->getClaimableBalanceId());
    }

    /**
     * @test
     * @covers ::withClaimableBalanceId
     * @covers ::getClaimableBalanceId
     */
    public function it_accepts_a_claimable_balance_id_string()
    {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e');

        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKeyClaimableBalance);
        $this->assertInstanceOf(ClaimableBalanceId::class, $ledgerKeyClaimableBalance->getClaimableBalanceId());
    }

    /**
     * @test
     * @covers ::withClaimableBalanceId
     * @covers ::getClaimableBalanceId
     */
    public function it_accepts_a_claimable_balance_id_hash()
    {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId(Hash::fromHex('0000000017496e54b6e193d074e50c47bad7ec8c411bd06ce984e6a9b4cd109e65569a4e'));

        $this->assertInstanceOf(LedgerKeyClaimableBalance::class, $ledgerKeyClaimableBalance);
        $this->assertInstanceOf(ClaimableBalanceId::class, $ledgerKeyClaimableBalance->getClaimableBalanceId());
    }
}
