<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\LedgerCloseMeta;
use StageRightLabs\Bloom\Ledger\LedgerCloseMetaV0;
use StageRightLabs\Bloom\Ledger\LedgerCloseMetaV1;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerCloseMeta
 */
class LedgerCloseMetaTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, LedgerCloseMeta::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => LedgerCloseMetaV0::class,
            1 => LedgerCloseMetaV1::class,
        ];

        $this->assertEquals($expected, LedgerCloseMeta::arms());
    }

    /**
     * @test
     * @covers ::wrapLedgerCloseMetaV0
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_close_meta_v0()
    {
        $ledgerCloseMeta = LedgerCloseMeta::wrapLedgerCloseMetaV0(new LedgerCloseMetaV0());

        $this->assertInstanceOf(LedgerCloseMeta::class, $ledgerCloseMeta);
        $this->assertInstanceOf(LedgerCloseMetaV0::class, $ledgerCloseMeta->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerCloseMetaV1
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_close_meta_v1()
    {
        $ledgerCloseMeta = LedgerCloseMeta::wrapLedgerCloseMetaV1(new LedgerCloseMetaV1());

        $this->assertInstanceOf(LedgerCloseMeta::class, $ledgerCloseMeta);
        $this->assertInstanceOf(LedgerCloseMetaV1::class, $ledgerCloseMeta->unwrap());
    }
}
