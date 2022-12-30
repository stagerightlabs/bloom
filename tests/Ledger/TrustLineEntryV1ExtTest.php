<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\TrustLineEntryExtensionV2;
use StageRightLabs\Bloom\Ledger\TrustLineEntryV1Ext;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TrustLineEntryV1Ext
 */
class TrustLineEntryV1ExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, TrustLineEntryV1Ext::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            2 => TrustLineEntryExtensionV2::class,
        ];

        $this->assertEquals($expected, TrustLineEntryV1Ext::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_no_underlying_value()
    {
        $trustLineEntryExtensionV1Ext = TrustLineEntryV1Ext::empty();
        $this->assertNull($trustLineEntryExtensionV1Ext->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTrustLineEntryExtensionV2
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v2()
    {
        $trustLineEntryExtensionV2 = new TrustLineEntryExtensionV2();
        $trustLineEntryExtensionV1Ext = (new TrustLineEntryV1Ext())
            ->wrapTrustLineEntryExtensionV2($trustLineEntryExtensionV2);

        $this->assertInstanceOf(TrustLineEntryExtensionV2::class, $trustLineEntryExtensionV1Ext->unwrap());
    }
}
