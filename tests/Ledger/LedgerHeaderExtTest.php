<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerHeaderExt;
use StageRightLabs\Bloom\Ledger\LedgerHeaderExtensionV1;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerHeaderExt
 */
class LedgerHeaderExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, LedgerHeaderExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            1 => LedgerHeaderExtensionV1::class,
        ];

        $this->assertEquals($expected, LedgerHeaderExt::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_empty_union()
    {
        $ledgerHeaderExt = LedgerHeaderExt::empty();
        $this->assertNull($ledgerHeaderExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerHeaderExtensionV1
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_header_extension_v1()
    {
        $ledgerHeaderExtensionV1 = (new LedgerHeaderExtensionV1())->withFlags(0);
        $ledgerHeaderExt = (new LedgerHeaderExt())->wrapLedgerHeaderExtensionV1($ledgerHeaderExtensionV1);

        $this->assertInstanceOf(LedgerHeaderExt::class, $ledgerHeaderExt);
        $this->assertInstanceOf(LedgerHeaderExtensionV1::class, $ledgerHeaderExt->unwrap());
    }
}
