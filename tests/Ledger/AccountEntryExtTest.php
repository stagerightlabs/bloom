<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\AccountEntryExt;
use StageRightLabs\Bloom\Ledger\AccountEntryExtensionV1;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\AccountEntryExt
 */
class AccountEntryExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, AccountEntryExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            1 => AccountEntryExtensionV1::class,
        ];

        $this->assertEquals($expected, AccountEntryExt::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_no_underlying_value()
    {
        $accountEntryExt = AccountEntryExt::empty();
        $this->assertNull($accountEntryExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapAccountEntryExtensionV1
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v1()
    {
        $accountEntryExtensionV1 = new AccountEntryExtensionV1();
        $accountEntryExt = (new AccountEntryExt())
            ->wrapAccountEntryExtensionV1($accountEntryExtensionV1);

        $this->assertInstanceOf(AccountEntryExtensionV1::class, $accountEntryExt->unwrap());
    }
}
