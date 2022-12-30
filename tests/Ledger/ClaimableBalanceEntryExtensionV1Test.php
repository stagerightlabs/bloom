<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExtensionV1;
use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExtensionV1Ext;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExtensionV1
 */
class ClaimableBalanceEntryExtensionV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $claimableBalanceEntryExtensionV1 = new ClaimableBalanceEntryExtensionV1();
        $buffer = XDR::fresh()->write($claimableBalanceEntryExtensionV1);

        $this->assertEquals('AAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $claimableBalanceEntryExtensionV1 = XDR::fromBase64('AAAAAAAAAAA=')
            ->read(ClaimableBalanceEntryExtensionV1::class);

        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1::class, $claimableBalanceEntryExtensionV1);
        $this->assertInstanceOf(UInt32::class, $claimableBalanceEntryExtensionV1->getFlags());
        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1Ext::class, $claimableBalanceEntryExtensionV1->getExtension());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_flags()
    {
        $claimableBalanceEntryExtensionV1 = (new ClaimableBalanceEntryExtensionV1())
            ->withFlags(UInt32::of(0));

        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1::class, $claimableBalanceEntryExtensionV1);
        $this->assertInstanceOf(UInt32::class, $claimableBalanceEntryExtensionV1->getFlags());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $claimableBalanceEntryExtensionV1 = (new ClaimableBalanceEntryExtensionV1())
            ->withExtension(ClaimableBalanceEntryExtensionV1Ext::empty());

        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1::class, $claimableBalanceEntryExtensionV1);
        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1Ext::class, $claimableBalanceEntryExtensionV1->getExtension());
    }
}
