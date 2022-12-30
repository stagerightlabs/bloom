<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2Ext;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV3;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExtensionV2Ext
 */
class AccountEntryExtensionV2ExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, AccountEntryExtensionV2Ext::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            3 => AccountEntryExtensionV3::class,
        ];

        $this->assertEquals($expected, AccountEntryExtensionV2Ext::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_empty_account_entry_extension_v2_ext()
    {
        $memo = AccountEntryExtensionV2Ext::empty();
        $this->assertNull($memo->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAccountEntryExtensionV3
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v3()
    {
        $accountEntryExtensionV3 = new AccountEntryExtensionV3();
        $accountEntryExtensionV2Ext = AccountEntryExtensionV2Ext::wrapAccountEntryExtensionV3($accountEntryExtensionV3);

        $this->assertInstanceOf(AccountEntryExtensionV2Ext::class, $accountEntryExtensionV2Ext);
        $this->assertInstanceOf(AccountEntryExtensionV3::class, $accountEntryExtensionV2Ext->unwrap());
    }
}
