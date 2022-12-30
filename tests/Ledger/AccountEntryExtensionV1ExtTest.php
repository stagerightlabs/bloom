<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1Ext;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1Ext
 */
class AccountEntryExtensionV1ExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, AccountEntryExtensionV1Ext::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            2 => AccountEntryExtensionV2::class,
        ];

        $this->assertEquals($expected, AccountEntryExtensionV1Ext::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_no_underlying_value()
    {
        $accountEntryExtensionV1Ext = AccountEntryExtensionV1Ext::empty();
        $this->assertNull($accountEntryExtensionV1Ext->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAccountEntryExtensionV2
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v2()
    {
        $accountEntryExtensionV2 = new AccountEntryExtensionV2();
        $accountEntryExtensionV1Ext = (new AccountEntryExtensionV1Ext())
            ->wrapAccountEntryExtensionV2($accountEntryExtensionV2);

        $this->assertInstanceOf(AccountEntryExtensionV2::class, $accountEntryExtensionV1Ext->unwrap());
    }
}
