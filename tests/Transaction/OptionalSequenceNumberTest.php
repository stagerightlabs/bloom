<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\OptionalSequenceNumber;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\OptionalSequenceNumber
 */
class OptionalSequenceNumberTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrValueType
     */
    public function it_defines_an_xdr_value_type()
    {
        $this->assertEquals(SequenceNumber::class, OptionalSequenceNumber::getXdrValueType());
    }

    /**
     * @test
     * @covers ::some
     */
    public function it_can_be_instantiated_from_a_ledger_bounds_object()
    {
        $sequenceNumber = SequenceNumber::of(1);
        $optional = OptionalSequenceNumber::some($sequenceNumber);

        $this->assertInstanceOf(OptionalSequenceNumber::class, $optional);
        $this->assertInstanceOf(SequenceNumber::class, $optional->unwrap());
    }

    /**
     * @test
     * @covers ::unwrap
     */
    public function it_returns_the_underlying_ledger_bounds_or_null()
    {
        $sequenceNumberA = OptionalSequenceNumber::none();
        $sequenceNumberB = OptionalSequenceNumber::some(SequenceNumber::of(1));

        $this->assertNull($sequenceNumberA->unwrap());
        $this->assertInstanceOf(SequenceNumber::class, $sequenceNumberB->unwrap());
    }
}
