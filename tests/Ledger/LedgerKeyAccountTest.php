<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKeyAccount;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerKeyAccount
 */
class LedgerKeyAccountTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ledgerKeyAccount = (new LedgerKeyAccount())->withAccountId($accountId);
        $buffer = XDR::fresh()->write($ledgerKeyAccount);

        $this->assertEquals('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_account_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new LedgerKeyAccount());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerKeyAccount = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53u')
            ->read(LedgerKeyAccount::class);

        $this->assertInstanceOf(LedgerKeyAccount::class, $ledgerKeyAccount);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyAccount->getAccountId());
    }

    /**
     * @test
     * @covers ::withAccountId
     * @covers ::getAccountId
     */
    public function it_accepts_an_account_id()
    {
        $accountId = AccountId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
        $ledgerKeyAccount = (new LedgerKeyAccount())->withAccountId($accountId);

        $this->assertInstanceOf(LedgerKeyAccount::class, $ledgerKeyAccount);
        $this->assertInstanceOf(AccountId::class, $ledgerKeyAccount->getAccountId());
    }
}
