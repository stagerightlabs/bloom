<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\LedgerBounds;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\LedgerBounds
 */
class LedgerBoundsTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ledgerBounds = LedgerBounds::between(1, 2);
        $buffer = XDR::fresh()->write($ledgerBounds)->toBase64();

        $this->assertEquals('AAAAAQAAAAI=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ledgerBounds = XDR::fromBase64('AAAAAQAAAAI=')->read(LedgerBounds::class);

        $this->assertInstanceOf(LedgerBounds::class, $ledgerBounds);
        $this->assertEquals(1, $ledgerBounds->getMinLedgerOffset()->toNativeInt());
        $this->assertEquals(2, $ledgerBounds->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::between
     */
    public function it_can_be_created_from_two_int_ledger_numbers()
    {
        $ledgerBounds = LedgerBounds::between(1, 2);

        $this->assertEquals(1, $ledgerBounds->getMinLedgerOffset()->toNativeInt());
        $this->assertEquals(2, $ledgerBounds->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::between
     */
    public function it_can_be_created_from_two_uint32_ledger_numbers()
    {
        $ledgerBounds = LedgerBounds::between(UInt32::of(1), UInt32::of(2));

        $this->assertEquals(1, $ledgerBounds->getMinLedgerOffset()->toNativeInt());
        $this->assertEquals(2, $ledgerBounds->getMaxLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMinLedgerOffset
     * @covers ::withMinLedgerOffset
     */
    public function it_accepts_a_uint32_as_minimum_ledger_number()
    {
        $ledgerBoundsA = new LedgerBounds();
        $number = UInt32::of(1);
        $ledgerBoundsB = $ledgerBoundsA->withMinLedgerOffset($number);

        $this->assertTrue($ledgerBoundsB->getMinLedgerOffset()->toNativeInt() == $number->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMinLedgerOffset
     * @covers ::withMinLedgerOffset
     */
    public function it_accepts_an_int_as_minimum_ledger_number()
    {
        $ledgerBoundsA = new LedgerBounds();
        $number = 1;
        $ledgerBoundsB = $ledgerBoundsA->withMinLedgerOffset($number);

        $this->assertTrue($ledgerBoundsB->getMinLedgerOffset()->toNativeInt() == $number);
    }

    /**
     * @test
     * @covers ::getMinLedgerOffset
     */
    public function the_min_ledger_defaults_to_zero()
    {
        $this->assertEquals(0, (new LedgerBounds())->getMinLedgerOffset()->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMinLedgerOffset
     * @covers ::withMinLedgerOffset
     */
    public function it_accepts_a_uint32_as_maximum_ledger_number()
    {
        $ledgerBoundsA = new LedgerBounds();
        $number = UInt32::of(1);
        $ledgerBoundsB = $ledgerBoundsA->withMaxLedgerOffset($number);

        $this->assertTrue($ledgerBoundsB->getMaxLedgerOffset()->toNativeInt() == $number->toNativeInt());
    }

    /**
     * @test
     * @covers ::getMaxLedgerOffset
     * @covers ::withMaxLedgerOffset
     */
    public function it_accepts_an_int_as_maximum_ledger_number()
    {
        $ledgerBoundsA = new LedgerBounds();
        $number = 1;
        $ledgerBoundsB = $ledgerBoundsA->withMaxLedgerOffset($number);

        $this->assertTrue($ledgerBoundsB->getMaxLedgerOffset()->toNativeInt() == $number);
    }

    /**
     * @test
     * @covers ::getMaxLedgerOffset
     */
    public function the_max_ledger_defaults_to_zero()
    {
        $this->assertEquals(0, (new LedgerBounds())->getMaxLedgerOffset()->toNativeInt());
    }
}
