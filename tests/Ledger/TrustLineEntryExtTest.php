<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Ledger\TrustLineEntryExt;
use StageRightLabs\Bloom\Ledger\TrustLineEntryV1;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TrustLineEntryExt
 */
class TrustLineEntryExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(XDR::INT, TrustLineEntryExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            0 => XDR::VOID,
            1 => TrustLineEntryV1::class,
        ];

        $this->assertEquals($expected, TrustLineEntryExt::arms());
    }

    /**
     * @test
     * @covers ::empty
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_with_no_underlying_value()
    {
        $trustLineEntryExt = TrustLineEntryExt::empty();
        $this->assertNull($trustLineEntryExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapTrustLineEntryV1
     * @covers ::unwrap
     */
    public function it_can_wrap_an_account_entry_extension_v2()
    {
        $trustLineEntryV1 = new TrustLineEntryV1();
        $trustLineEntryExt = (new TrustLineEntryExt())
            ->wrapTrustLineEntryV1($trustLineEntryV1);

        $this->assertInstanceOf(TrustLineEntryV1::class, $trustLineEntryExt->unwrap());
    }
}
