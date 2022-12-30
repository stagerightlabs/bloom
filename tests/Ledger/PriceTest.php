<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\Price
 */
class PriceTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_method()
    {
        $price = Price::of(1, 2);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(1, $price->getNumerator()->toNativeInt());
        $this->assertEquals(2, $price->getDenominator()->toNativeInt());
    }

    /**
     * @test
     * @covers ::rationalize
     * @covers ::fromNativeString
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $price = Price::rationalize('.36');
        $this->assertEquals(9, $price->getNumerator()->toNativeInt());
        $this->assertEquals(25, $price->getDenominator()->toNativeInt());

        $price = Price::rationalize('9/25');
        $this->assertEquals(9, $price->getNumerator()->toNativeInt());
        $this->assertEquals(25, $price->getDenominator()->toNativeInt());

        $price = Price::fromNativeString('3.75');
        $this->assertEquals(15, $price->getNumerator()->toNativeInt());
        $this->assertEquals(4, $price->getDenominator()->toNativeInt());

        $price = Price::fromNativeString('15/4');
        $this->assertEquals(15, $price->getNumerator()->toNativeInt());
        $this->assertEquals(4, $price->getDenominator()->toNativeInt());

        $price = Price::fromNativeString('0.284');
        $this->assertEquals(71, $price->getNumerator()->toNativeInt());
        $this->assertEquals(250, $price->getDenominator()->toNativeInt());

        $price = Price::fromNativeString('71/250');
        $this->assertEquals(71, $price->getNumerator()->toNativeInt());
        $this->assertEquals(250, $price->getDenominator()->toNativeInt());
    }

    /**
     * @test
     * @covers ::rationalize
     */
    public function it_rejects_ratios_that_contain_values_less_than_one()
    {
        $this->expectException(InvalidArgumentException::class);
        Price::fromNativeString('1/0');
    }

    /**
     * @test
     * @covers ::rationalize
     */
    public function it_rejects_ratios_that_contain_decimals()
    {
        $this->expectException(InvalidArgumentException::class);
        Price::fromNativeString('1/1.1');
    }


    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $price = (new Price())
            ->withNumerator(Int32::of(1))
            ->withDenominator(Int32::of(2));
        $buffer = XDR::fresh()->write($price);

        $this->assertEquals('AAAAAQAAAAI=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_numerator_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $price = (new Price())->withDenominator(Int32::of(2));
        XDR::fresh()->write($price);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_denominator_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $price = (new Price())->withNumerator(Int32::of(1));
        XDR::fresh()->write($price);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $price = XDR::fromBase64('AAAAAQAAAAI=')->read(Price::class);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals(1, $price->getNumerator()->toNativeInt());
        $this->assertEquals(2, $price->getDenominator()->toNativeInt());
    }

    /**
     * @test
     * @covers ::withNumerator
     * @covers ::getNumerator
     */
    public function it_accepts_a_numerator()
    {
        $price = (new Price())->withNumerator(Int32::of(1));
        $this->assertInstanceOf(Int32::class, $price->getNumerator());
    }

    /**
     * @test
     * @covers ::withDenominator
     * @covers ::getDenominator
     */
    public function it_accepts_a_denominator()
    {
        $price = (new Price())->withDenominator(Int32::of(2));
        $this->assertInstanceOf(Int32::class, $price->getDenominator());
    }

    /**
     * @test
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_string()
    {
        $this->assertEquals('0.3600000', Price::of(9, 25)->toNativeString());
        $this->assertEquals('3.7500000', Price::of(15, 4)->toNativeString());
        $this->assertEquals('0.2840000', Price::of(71, 250)->toNativeString());
    }
}
