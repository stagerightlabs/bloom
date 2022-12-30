<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExt;
use StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExtensionV1;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\ClaimableBalanceEntryExt
 */
class ClaimableBalanceEntryExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, ClaimableBalanceEntryExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            1 => ClaimableBalanceEntryExtensionV1::class,
        ];

        $this->assertEquals($expected, ClaimableBalanceEntryExt::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_no_underlying_value()
    {
        $claimableBalanceEntryExt = ClaimableBalanceEntryExt::empty();
        $this->assertNull($claimableBalanceEntryExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapClaimableBalanceEntryExtensionV1
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v2()
    {
        $claimableBalanceEntryExtensionV1 = new ClaimableBalanceEntryExtensionV1();
        $claimableBalanceEntryExt = (new ClaimableBalanceEntryExt())
            ->wrapClaimableBalanceEntryExtensionV1($claimableBalanceEntryExtensionV1);

        $this->assertInstanceOf(ClaimableBalanceEntryExtensionV1::class, $claimableBalanceEntryExt->unwrap());
    }
}
