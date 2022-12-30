<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Ledger\LedgerCloseValueSignature;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\StellarValueExt;
use StageRightLabs\Bloom\SCP\StellarValueType;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\StellarValueExt
 */
class StellarValueExtTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(StellarValueType::class, StellarValueExt::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            StellarValueType::STELLAR_VALUE_BASIC  => XDR::VOID,
            StellarValueType::STELLAR_VALUE_SIGNED => LedgerCloseValueSignature::class,
        ];

        $this->assertEquals($expected, StellarValueExt::arms());
    }

    /**
     * @test
     * @covers ::basic
     * @covers ::unwrap
     */
    public function it_can_be_instantiated_as_an_empty_union()
    {
        $stellarValueExt = StellarValueExt::basic();
        $this->assertNull($stellarValueExt->unwrap());
    }

    /**
     * @test
     * @covers ::wrapLedgerCloseValueSignature
     * @covers ::unwrap
     */
    public function it_can_wrap_a_ledger_close_value_signature()
    {
        $ledgerCloseValueSignature = (new LedgerCloseValueSignature())
            ->withNodeId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSignature(Signature::of('abcd'));
        $stellarValueExt = StellarValueExt::wrapLedgerCloseValueSignature($ledgerCloseValueSignature);

        $this->assertInstanceOf(StellarValueExt::class, $stellarValueExt);
        $this->assertInstanceOf(LedgerCloseValueSignature::class, $ledgerCloseValueSignature);
    }
}
