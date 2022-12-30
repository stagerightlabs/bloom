<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Utility;

use StageRightLabs\Bloom\Exception\InvalidAmountException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Utility\Number;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Utility\Number
 */
class NumberTest extends TestCase
{
    /**
     * @test
     * @covers ::isValidAmount
     * @dataProvider provideAmountValidationData
     */
    public function it_validates_amounts($amount, $expected)
    {
        $this->assertEquals($expected, Number::isValidAmount($amount));
    }

    /**
     * @test
     * @covers ::isValidAmount
     */
    public function it_can_reject_zero_amounts()
    {
        $this->assertFalse(Number::isValidAmount('0'));
        $this->assertTrue(Number::isValidAmount('0', $allowZero = true));
    }

    /**
     * @test
     * @covers ::isValidAmount
     * @covers ::descale
     */
    public function it_rejects_amounts_greater_than_max_int64()
    {
        $this->assertFalse(Number::isValidAmount('9223372036854775808'));
    }

    /**
     * @test
     * @covers ::isValidAmount
     */
    public function is_valid_amount_rejects_amounts_with_more_than_seven_decimal_places()
    {
        $this->assertFalse(Number::isValidAmount('1.12345678'));
    }

    /**
     * @test
     * @covers ::scale
     * @dataProvider provideIntegerToAmountData
     */
    public function it_converts_integers_to_amounts(string $integer, string $expectedWithoutCommas, string $expectedWithCommas)
    {
        $string = UInt64::of($integer);

        $amount = Number::scale($string, $commas = false);
        $this->assertEquals($expectedWithoutCommas, $amount);

        $amount = Number::scale($string, $commas = true);
        $this->assertEquals($expectedWithCommas, $amount);
    }

    /**
     * @test
     * @covers ::descale
     * @dataProvider provideAmountToIntegerData
     */
    public function it_converts_amounts_to_integers(string $amount, string $expected)
    {
        $integer = Number::descale($amount);

        $this->assertTrue($integer->isEqualTo($expected));
    }

    /**
     * @test
     * @covers ::descale
     *
     * @return void
     */
    public function it_does_not_convert_amounts_that_exceed_the_allowed_maximum()
    {
        $this->expectException(InvalidAmountException::class);
        Number::descale('922,337,203,685.4775808');
    }

    /**
     * @test
     * @covers ::descale
     */
    public function integer_from_amount_rejects_amounts_with_more_than_seven_decimal_places()
    {
        $this->expectException(InvalidAmountException::class);
        Number::descale('1.12345678');
    }

    /**
     * @test
     * @covers ::decimalPlaceCount
     * @dataProvider provideDecimalPlaceData
     */
    public function it_counts_decimal_places($amount, $count)
    {
        $this->assertEquals($count, Number::decimalPlaceCount($amount));
    }

    /**
     * @test
     * @covers ::trimZeroes
     * @dataProvider provideTrimZeroesData
     */
    public function it_removes_leading_and_trailing_zeros($amount, $expected)
    {
        $this->assertEquals($expected, Number::trimZeroes($amount));
    }

    /**
     * @test
     * @covers ::isDecimal
     * @dataProvider provideDecimalsData
     */
    public function it_confirms_whether_values_are_decimals($number, $expected)
    {
        $this->assertEquals($expected, Number::isDecimal($number));
    }

    /**
     * @return array<array<string, bool>>
     */
    public function provideAmountValidationData()
    {
        return [
            ['abcd123', false],
            ['922,337,203,685.4775807', true],
            ['922337203685.4775807', true],
            ['922337203685.4775808', false],
            ['922337203685.12345678', false],
            ['-100', false],
        ];
    }

    /**
     * @return array<array<string, string, string>>
     */
    public function provideIntegerToAmountData()
    {
        return [
            ['1', '.0000001', '.0000001'],
            ['1000000', '.1000000', '.1000000'],
            ['10000000', '1.0000000', '1.0000000'],
            ['2345600000', '234.5600000', '234.5600000'],
            ['1000000000', '100.0000000', '100.0000000'],
            ['9223372036854775807', '922337203685.4775807', '922,337,203,685.4775807']
        ];
    }

    /**
     * @return array<array<string, string>>
     */
    public function provideAmountToIntegerData()
    {
        return [
            ['.0000001', '1'],
            ['1', '10000000'],
            ['234.56', '2345600000'],
            ['100.00', '1000000000'],
            ['922,337,203,685.4775807', '9223372036854775807']
        ];
    }

    /**
     * @return array<array<string, int>>
     */
    public function provideDecimalPlaceData()
    {
        return [
            ['.0000001', 7],
            ['.000001', 6],
            ['.00001', 5],
            ['.0001', 4],
            ['.001', 3],
            ['.01', 2],
            ['.1', 1],
            ['1', 0]
        ];
    }

    /**
     * @return array<array<string, string>>
     */
    public function provideTrimZeroesData()
    {
        return [
            ['100.00', '100'],
            ['0001.230', '1.23'],
            ['3.7500000', '3.75'],
        ];
    }

    /**
     * @return array<>
     */
    public function provideDecimalsData()
    {
        return [
            [1, false,],
            [1.0, true],
            ['1', false],
            ['1.0', true],
        ];
    }
}
