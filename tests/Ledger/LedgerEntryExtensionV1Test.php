<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\SponsorshipDescriptor;
use StageRightLabs\Bloom\Ledger\LedgerEntryExtensionV1;
use StageRightLabs\Bloom\Ledger\LedgerEntryExtensionV1Ext;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerEntryExtensionV1
 */
class LedgerEntryExtensionV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerEntryExtensionV1 = new LedgerEntryExtensionV1();
        $buffer = XDR::fresh()->write($ledgerEntryExtensionV1);

        $this->assertEquals('AAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerEntryExtensionV1 = XDR::fromBase64('AAAAAAAAAAA=')->read(LedgerEntryExtensionV1::class);

        $this->assertInstanceOf(LedgerEntryExtensionV1::class, $ledgerEntryExtensionV1);
        $this->assertInstanceOf(SponsorshipDescriptor::class, $ledgerEntryExtensionV1->getSponsoringId());
        $this->assertInstanceOf(LedgerEntryExtensionV1Ext::class, $ledgerEntryExtensionV1->getExtension());
    }

    /**
     * @test
     * @covers ::withSponsoringId
     * @covers ::getSponsoringId
     */
    public function it_accepts_a_sponsoring_id()
    {
        $ledgerEntryExtensionV1 = (new LedgerEntryExtensionV1())
            ->withSponsoringId(SponsorshipDescriptor::some('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(LedgerEntryExtensionV1::class, $ledgerEntryExtensionV1);
        $this->assertInstanceOf(SponsorshipDescriptor::class, $ledgerEntryExtensionV1->getSponsoringId());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_a_ledger_entry_extension_v1_ext()
    {
        $ledgerEntryExtensionV1 = (new LedgerEntryExtensionV1())
            ->withExtension(LedgerEntryExtensionV1Ext::empty());

        $this->assertInstanceOf(LedgerEntryExtensionV1::class, $ledgerEntryExtensionV1);
        $this->assertInstanceOf(LedgerEntryExtensionV1Ext::class, $ledgerEntryExtensionV1->getExtension());
    }
}
