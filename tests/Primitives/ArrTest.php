<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Primitives\Arr;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\MemoType;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Arr
 */
class ArrTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     */
    public function it_is_instantiated_with_an_empty_array_value()
    {
        $this->assertTrue(is_array((new FixedLengthIntArr())->toArray()));
        $this->assertEmpty((new FixedLengthIntArr())->toArray());
    }

    /**
     * @test
     * @covers ::of
     * @covers ::make
     * @covers ::withArray
     */
    public function it_wraps_native_arrays()
    {
        $arr = FixedLengthIntArr::of([1, 2, 3]);

        $this->assertInstanceOf(FixedLengthIntArr::class, $arr);
        $this->assertEquals(0, $arr->key());
    }

    /**
     * @test
     * @covers ::of
     * @covers ::withArray
     * @covers ::clone
     */
    public function it_wraps_arrays_of_objects()
    {
        $enum = MemoType::text();
        $arr = FixedLengthIntArr::of([$enum]);

        $this->assertInstanceOf(FixedLengthIntArr::class, $arr);
        $this->assertInstanceOf(MemoType::class, $arr->get(0));
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_wraps_non_arrays()
    {
        $arr = FixedLengthIntArr::of(1);

        $this->assertInstanceOf(FixedLengthIntArr::class, $arr);
        $this->assertEquals(1, $arr->count());
    }

    /**
     * @test
     * @covers ::of
     * @covers ::withArray
     * @covers ::clone
     */
    public function it_wraps_arrays_of_arrays()
    {
        $arr = FixedLengthIntArr::of([['a', 'b', 'c'], [1, 2, 3]]);

        $this->assertInstanceOf(FixedLengthIntArr::class, $arr);
        $this->assertIsArray($arr->get(0));
    }

    /**
     * @test
     * @covers ::withArray
     */
    public function it_strips_keys_from_associative_arrays()
    {
        $arr = FixedLengthIntArr::of(['one' => 1, 'two' => 2]);
        $this->assertEquals([1, 2], $arr->toArray());
    }

    /**
     * @test
     * @covers ::withArray
     */
    public function it_rejects_arrays_longer_than_the_defined_limit()
    {
        $this->expectException(InvalidArgumentException::class);
        FixedLengthIntArr::of([1, 2, 3, 4, 5, 6]);
    }

    /**
     * @test
     * @covers ::get
     */
    public function it_return_elements_from_the_underlying_array()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);

        $this->assertEquals(1, $arr->get(0));
        $this->assertEquals(4, $arr->get(3, 4));
    }

    /**
     * @test
     * @covers ::with
     */
    public function it_adds_items_to_the_underlying_array_at_a_given_index()
    {
        $first = VariableLengthArr::of([1, 2, 3]);
        $second = $first->with(0, 100);

        $this->assertInstanceOf(VariableLengthArr::class, $second);
        $this->assertEquals([100, 2, 3], $second->toArray());
        $this->assertEquals([1, 2, 3], $first->toArray());
    }

    /**
     * @test
     * @covers ::push
     */
    public function it_appends_values_to_the_underlying_array()
    {
        $arr = FixedLengthIntArr::of([1, 2, 3]);
        $arr = $arr->push(4);

        $this->assertEquals(4, $arr->count());
    }

    /**
     * @test
     * @covers ::push
     */
    public function it_will_not_accept_more_elements_than_the_defined_limit()
    {
        $this->expectException(UnexpectedValueException::class);
        $arr = FixedLengthIntArr::of([1, 2, 3, 4, 5]);
        $arr->push(6);
    }

    /**
     * @test
     * @covers ::getMaxLength
     */
    public function it_has_a_defined_length_limit()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $this->assertEquals(XDR::MAX_LENGTH, $arr->getMaxLength());
    }

    /**
     * @test
     * @covers ::getXdrArray
     * @covers ::getXdrLength
     */
    public function it_can_be_converted_to_xdr()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $buffer = XDR::fresh()->write($arr)->toBase64();

        $this->assertEquals('AAAAAwAAAAEAAAACAAAAAw==', $buffer);
    }

    /**
     * @test
     * @covers ::getXdrArray
     * @covers ::getXdrLength
     */
    public function it_can_be_converted_to_xdr_as_a_fixed_length_array()
    {
        $arr = FixedLengthStringArr::of(['one', 'two', 'three', 'four', 'five']);
        $buffer = XDR::fresh()->write($arr, FixedLengthStringArr::class)->toBase64();

        $this->assertEquals('AAAAA29uZQAAAAADdHdvAAAAAAV0aHJlZQAAAAAAAARmb3VyAAAABGZpdmU=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $variableArr = XDR::fromBase64('AAAAAwAAAAEAAAACAAAAAw==')
            ->read(VariableLengthArr::class);
        $this->assertInstanceOf(VariableLengthArr::class, $variableArr);
        $this->assertEquals([1, 2, 3], $variableArr->toArray());

        $fixedArr = XDR::fromBase64('AAAAA29uZQAAAAADdHdvAAAAAAV0aHJlZQAAAAAAAARmb3VyAAAABGZpdmU=')
            ->read(FixedLengthStringArr::class);
        $this->assertInstanceOf(FixedLengthStringArr::class, $fixedArr);
        $this->assertEquals(['one', 'two', 'three', 'four', 'five'], $fixedArr->toArray());
    }

    /**
     * @test
     * @covers ::getXdrTypeLength
     */
    public function it_can_define_a_fixed_length_for_its_underlying_value_type()
    {
        $arr = FixedLengthIntArr::of([1, 2, 3]);
        $this->assertNull($arr->getXdrTypeLength());
    }

    /**
     * @test
     * @covers ::count
     */
    public function it_knows_the_count_of_the_underlying_array()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);

        $this->assertEquals(3, $arr->count());
    }

    /**
     * @test
     * @covers ::current
     */
    public function it_returns_the_item_at_the_current_cursor_position()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $this->assertEquals(1, $arr->current());

        $arr->next();
        $this->assertEquals(2, $arr->current());
    }

    /**
     * @test
     * @covers ::key
     */
    public function it_returns_the_current_cursor_position()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $this->assertEquals(0, $arr->key());

        $arr->next();
        $this->assertEquals(1, $arr->key());
    }

    /**
     * @test
     * @covers ::next
     */
    public function it_advances_the_current_cursor_position()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $this->assertEquals(1, $arr->current());

        $arr->next();
        $this->assertEquals(2, $arr->current());
    }

    /**
     * @test
     * @covers ::rewind
     */
    public function it_resets_the_current_cursor_position()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);

        $arr->next();
        $this->assertEquals(2, $arr->current());

        $arr->rewind();
        $this->assertEquals(1, $arr->current());
    }

    /**
     * @test
     * @covers ::valid
     */
    public function it_validates_the_current_cursor_position()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $arr->next(); // 2
        $arr->next(); // 3
        $arr->next(); // invalid
        $valid = $arr->valid();

        $this->assertFalse($valid);
    }

    /**
     * @test
     * @covers ::offsetExists
     */
    public function it_can_determine_if_an_offset_exists()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);

        $this->assertTrue($arr->offsetExists(0));
        $this->assertFalse($arr->offsetExists(4));
    }

    /**
     * @test
     * @covers ::offsetGet
     */
    public function it_can_retrieve_the_value_from_a_given_offset()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $this->assertEquals(1, $arr->offsetGet(0));
    }

    /**
     * @test
     * @covers ::offsetSet
     */
    public function it_can_set_a_value_at_a_given_offset()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $arr->offsetSet(0, 10);
        $arr->offsetSet(null, 4);

        $this->assertEquals(10, $arr->offsetGet(0));
        $this->assertEquals(4, $arr->offsetGet(3));
    }

    /**
     * @test
     * @covers ::offsetUnset
     */
    public function it_can_unset_a_value_from_a_given_offset()
    {
        $arr = VariableLengthArr::of([1, 2, 3]);
        $arr->offsetUnset(0);

        $this->assertEquals(2, $arr->count());
    }

    /**
     * @test
     * @covers ::isEmpty
     * @covers ::isNotEmpty
     */
    public function it_knows_when_it_is_empty()
    {
        $arrA = VariableLengthArr::of([1, 2, 3]);
        $arrB = VariableLengthArr::of([]);

        $this->assertTrue($arrA->isNotEmpty());
        $this->assertFalse($arrA->isEmpty());
        $this->assertTrue($arrB->isEmpty());
        $this->assertFalse($arrB->isNotEmpty());
    }

    /**
     * @test
     * @covers ::toArray
     */
    public function it_returns_the_underlying_array()
    {
        $one = VariableLengthArr::of([1, 2, 3]);

        $this->assertEquals([1, 2, 3], $one->toArray());
    }
}


class FixedLengthIntArr extends Arr
{
    public static function getXdrType(): string
    {
        return XDR::INT;
    }

    public static function getMaxLength(): int
    {
        return 5;
    }
}

class FixedLengthStringArr extends Arr
{
    public static function getXdrType(): string
    {
        return XDR::STRING;
    }

    public static function getXdrLength(): int
    {
        return 5;
    }
}

class VariableLengthArr extends Arr
{
    public static function getXdrType(): string
    {
        return XDR::INT;
    }
}
