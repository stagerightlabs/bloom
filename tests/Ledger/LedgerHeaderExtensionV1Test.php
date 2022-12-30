<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerHeaderExtensionV1;
use StageRightLabs\Bloom\Ledger\LedgerHeaderExtensionV1Ext;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\LedgerHeaderExtensionV1
 */
class LedgerHeaderExtensionV1Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerHeaderExtensionV1 = (new LedgerHeaderExtensionV1())->withFlags(0);
        $buffer = XDR::fresh()->write($ledgerHeaderExtensionV1);

        $this->assertEquals('AAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function flags_are_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new LedgerHeaderExtensionV1());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerHeaderExtensionV1 = XDR::fromBase64('AAAAAAAAAAA=')
            ->read(LedgerHeaderExtensionV1::class);

        $this->assertInstanceOf(LedgerHeaderExtensionV1::class, $ledgerHeaderExtensionV1);
        $this->assertInstanceOf(UInt32::class, $ledgerHeaderExtensionV1->getFlags());
        $this->assertInstanceOf(LedgerHeaderExtensionV1Ext::class, $ledgerHeaderExtensionV1->getExtension());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_a_uint32_as_flags()
    {
        $ledgerHeaderExtensionV1 = (new LedgerHeaderExtensionV1())
            ->withFlags(UInt32::of(0));

        $this->assertInstanceOf(LedgerHeaderExtensionV1::class, $ledgerHeaderExtensionV1);
        $this->assertInstanceOf(UInt32::class, $ledgerHeaderExtensionV1->getFlags());
    }

    /**
     * @test
     * @covers ::withFlags
     * @covers ::getFlags
     */
    public function it_accepts_a_native_int_as_flags()
    {
        $ledgerHeaderExtensionV1 = (new LedgerHeaderExtensionV1())
            ->withFlags(0);

        $this->assertInstanceOf(LedgerHeaderExtensionV1::class, $ledgerHeaderExtensionV1);
        $this->assertInstanceOf(UInt32::class, $ledgerHeaderExtensionV1->getFlags());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $ledgerHeaderExtensionV1 = (new LedgerHeaderExtensionV1())
            ->withExtension(LedgerHeaderExtensionV1Ext::empty());

        $this->assertInstanceOf(LedgerHeaderExtensionV1::class, $ledgerHeaderExtensionV1);
        $this->assertInstanceOf(LedgerHeaderExtensionV1Ext::class, $ledgerHeaderExtensionV1->getExtension());
    }
}
