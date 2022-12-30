<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Transaction;

use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\TxSetComponentType;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Transaction\TxSetComponentType
 */
class TxSetComponentTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0 => TxSetComponentType::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE,
        ];
        $txSetComponentType = new TxSetComponentType();

        $this->assertEquals($expected, $txSetComponentType->getOptions());
    }

    /**
     * @test
     * @covers ::maybeDiscountedFee
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_maybe_discounted_fee_type()
    {
        $txSetComponentType = TxSetComponentType::maybeDiscountedFee();

        $this->assertInstanceOf(TxSetComponentType::class, $txSetComponentType);
        $this->assertEquals(TxSetComponentType::TXSET_COMP_TXS_MAYBE_DISCOUNTED_FEE, $txSetComponentType->getType());
    }
}
