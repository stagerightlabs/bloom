<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\Liabilities;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1Ext;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1
 */
class AccountEntryExtensionV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $liabilities = (new Liabilities())
            ->withBuying(Int64::of(1))
            ->withSelling(Int64::of(2));
        $accountEntryExtensionV1 = (new AccountEntryExtensionV1())
            ->withLiabilities($liabilities);
        $buffer = XDR::fresh()->write($accountEntryExtensionV1);

        $this->assertEquals('AAAAAAAAAAEAAAAAAAAAAgAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function liabilities_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new AccountEntryExtensionV1());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $accountEntryExtensionV1 = XDR::fromBase64('AAAAAAAAAAEAAAAAAAAAAgAAAAA=')
            ->read(AccountEntryExtensionV1::class);

        $this->assertInstanceOf(AccountEntryExtensionV1::class, $accountEntryExtensionV1);
        $this->assertInstanceOf(Liabilities::class, $accountEntryExtensionV1->getLiabilities());
        $this->assertInstanceOf(AccountEntryExtensionV1Ext::class, $accountEntryExtensionV1->getExtension());
    }

    /**
     * @test
     * @covers ::withLiabilities
     * @covers ::getLiabilities
     */
    public function it_accepts_liabilities()
    {
        $accountEntryExtensionV1 = (new AccountEntryExtensionV1())
            ->withLiabilities(new Liabilities());

        $this->assertInstanceOf(Liabilities::class, $accountEntryExtensionV1->getLiabilities());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_account_entry_extension_v1_ext()
    {
        $accountEntryExtensionV1 = (new AccountEntryExtensionV1())
            ->withExtension(new AccountEntryExtensionV1Ext());

        $this->assertInstanceOf(AccountEntryExtensionV1Ext::class, $accountEntryExtensionV1->getExtension());
    }
}
