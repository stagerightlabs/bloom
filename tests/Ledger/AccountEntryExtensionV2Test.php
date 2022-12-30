<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Account\SponsorshipDescriptorList;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2Ext;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2
 */
class AccountEntryExtensionV2Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $accountEntryExtensionV2 = new AccountEntryExtensionV2();
        $buffer = XDR::fresh()->write($accountEntryExtensionV2);

        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $accountEntryExtensionV2 = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAA==')
            ->read(AccountEntryExtensionV2::class);

        $this->assertInstanceOf(AccountEntryExtensionV2::class, $accountEntryExtensionV2);
        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV2->getNumSponsored());
        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV2->getNumSponsoring());
        $this->assertInstanceOf(SponsorshipDescriptorList::class, $accountEntryExtensionV2->getSignerSponsoringIds());
        $this->assertInstanceOf(AccountEntryExtensionV2Ext::class, $accountEntryExtensionV2->getExtension());
    }

    /**
     * @test
     * @covers ::withNumSponsored
     * @covers ::getNumSponsored
     */
    public function it_accepts_a_number_sponsored()
    {
        $accountEntryExtensionV2 = (new AccountEntryExtensionV2())
            ->withNumSponsored(UInt32::of(1));

        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV2->getNumSponsored());
    }

    /**
     * @test
     * @covers ::withNumSponsoring
     * @covers ::getNumSponsoring
     */
    public function it_accepts_a_number_sponsoring()
    {
        $accountEntryExtensionV2 = (new AccountEntryExtensionV2())
            ->withNumSponsoring(UInt32::of(2));

        $this->assertInstanceOf(UInt32::class, $accountEntryExtensionV2->getNumSponsoring());
    }

    /**
     * @test
     * @covers ::withSignerSponsoringIds
     * @covers ::getSignerSponsoringIds
     */
    public function it_accepts_a_list_of_signer_sponsoring_ids()
    {
        $accountEntryExtensionV2 = (new AccountEntryExtensionV2())
            ->withSignerSponsoringIds(SponsorshipDescriptorList::empty());

        $this->assertInstanceOf(SponsorshipDescriptorList::class, $accountEntryExtensionV2->getSignerSponsoringIds());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $accountEntryExtensionV2 = (new AccountEntryExtensionV2())
            ->withExtension(AccountEntryExtensionV2Ext::empty());

        $this->assertInstanceOf(AccountEntryExtensionV2Ext::class, $accountEntryExtensionV2->getExtension());
    }
}
