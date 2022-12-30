<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\LedgerBounds;
use StageRightLabs\Bloom\Transaction\OptionalLedgerBounds;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\OptionalLedgerBounds
 */
class OptionalLedgerBoundsTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(LedgerBounds::class, OptionalLedgerBounds::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_ledger_bounds_object()
    {
        $ledgerBounds = LedgerBounds::between(1, 2);
        $optional = OptionalLedgerBounds::some($ledgerBounds);

        $this->assertInstanceOf(OptionalLedgerBounds::class, $optional);
        $this->assertInstanceOf(LedgerBounds::class, $optional->unwrap());
    }


    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_underlying_ledger_bounds_or_null()
    {
        $ledgerBoundsA = OptionalLedgerBounds::none();
        $ledgerBoundsB = OptionalLedgerBounds::some(LedgerBounds::between(1, 2));

        $this->assertNull($ledgerBoundsA->unwrap());
        $this->assertInstanceOf(LedgerBounds::class, $ledgerBoundsB->unwrap());
    }
}
