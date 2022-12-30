<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Enumeration
 */
class EnumerationTest extends TestCase
{
    /**
     * @test
     * @covers ::isValid
     */
    public function it_can_determine_if_a_selection_is_valid_when_given_an_int()
    {
        $enum = new ExampleEnumeration();

        $this->assertTrue($enum::isValid(0));
        $this->assertFalse($enum::isValid(255));
    }

    /**
     * @test
     * @covers ::isValid
     */
    public function it_can_determine_if_a_selection_is_valid_when_given_a_string_value()
    {
        $enum = new ExampleEnumeration();

        $this->assertTrue($enum::isValid(ExampleEnumeration::EXAMPLE_A));
        $this->assertFalse($enum::isValid('invalid-memo-type'));
    }

    /**
     * @test
     * @covers ::getXdrSelection
     * @covers ::isValidXdrSelection
     */
    public function it_can_be_converted_to_xdr()
    {
        $enum = ExampleEnumeration::a();
        $buffer = XDR::fresh()->write($enum)->toBase64();

        $this->assertEquals('AAAAAA==', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $enum = XDR::fromBase64('AAAAAA==')->read(ExampleEnumeration::class);
        $this->assertInstanceOf(ExampleEnumeration::class, $enum);
    }

    /**
     * @test
     * @covers ::getIndex
     */
    public function it_returns_its_index_value()
    {
        $enum = ExampleEnumeration::b();
        $this->assertEquals(1, $enum->getProtectedIndex());
    }

    /**
     * @test
     * @covers ::getIndex
     */
    public function attempting_to_return_an_index_without_a_selection_throws_an_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        (new ExampleEnumeration())->getProtectedIndex();
    }

    /**
     * @test
     * @covers ::getValue
     */
    public function it_returns_the_selected_value()
    {
        $enum = ExampleEnumeration::b();
        $this->assertEquals(ExampleEnumeration::EXAMPLE_B, $enum->getProtectedValue());
    }

    /**
     * @test
     * @covers ::__toString
     */
    public function it_can_be_converted_to_a_native_string_using_the_underlying_value()
    {
        $enum = ExampleEnumeration::b();
        $this->assertEquals($enum->getProtectedValue(), strval($enum));
    }

    /**
     * @test
     * @covers ::withSelection
     */
    public function the_selection_can_be_set()
    {
        $enumA = (new ExampleEnumeration())->withProtectedSelection(1);
        $enumB = $enumA->withProtectedSelection(2);

        $this->assertEquals(ExampleEnumeration::EXAMPLE_B, $enumA->getProtectedValue());
        $this->assertEquals(ExampleEnumeration::EXAMPLE_C, $enumB->getProtectedValue());
    }

    /**
     * @test
     * @covers ::withSelection
     */
    public function it_rejects_invalid_selections()
    {
        $this->expectException(InvalidArgumentException::class);
        (new ExampleEnumeration())->withProtectedSelection(255); // invalid
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function the_selection_can_be_set_via_value()
    {
        $enumA = (new ExampleEnumeration())->withProtectedValue(ExampleEnumeration::EXAMPLE_A);
        $enumB = $enumA->withProtectedValue(ExampleEnumeration::EXAMPLE_B);

        $this->assertEquals(ExampleEnumeration::EXAMPLE_A, $enumA->getProtectedValue());
        $this->assertEquals(ExampleEnumeration::EXAMPLE_B, $enumB->getProtectedValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_rejects_invalid_values()
    {
        $this->expectException(InvalidArgumentException::class);
        (new ExampleEnumeration())->withProtectedValue('invalid');
    }

    /**
     * @test
     * @covers ::getDefaultSelection
     */
    public function it_can_specify_a_default_selection()
    {
        $this->assertNull(ExampleEnumeration::getProtectedDefaultSelection());
        $this->assertNotNull(ExampleEnumerationWithDefault::getProtectedDefaultSelection());
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_with_a_preselected_value()
    {
        $exampleFromInt = ExampleEnumeration::of(1);
        $this->assertInstanceOf(ExampleEnumeration::class, $exampleFromInt);
        $this->assertEquals(ExampleEnumeration::EXAMPLE_B, $exampleFromInt->getProtectedValue());

        $exampleFromString = ExampleEnumeration::of(ExampleEnumeration::EXAMPLE_A);
        $this->assertInstanceOf(ExampleEnumeration::class, $exampleFromString);
        $this->assertEquals(ExampleEnumeration::EXAMPLE_A, $exampleFromString->getProtectedValue());
    }

    /**
     * @test
     * @covers ::is
     */
    public function it_can_determine_if_its_content_matches_a_given_value()
    {
        $enumeration = ExampleEnumeration::of(ExampleEnumeration::EXAMPLE_A);

        $this->assertTrue($enumeration->is(ExampleEnumeration::EXAMPLE_A));
        $this->assertFalse($enumeration->is(ExampleEnumeration::EXAMPLE_B));
        $this->assertTrue($enumeration->is(0));
        $this->assertFalse($enumeration->is(1));
    }
}


class ExampleEnumeration extends Enumeration implements XdrEnum
{
    public const EXAMPLE_A = 'exampleA';
    public const EXAMPLE_B = 'exampleB';
    public const EXAMPLE_C = 'exampleC';

    public static function getOptions(): array
    {
        return [
            0 => self::EXAMPLE_A,
            1 => self::EXAMPLE_B,
            2 => self::EXAMPLE_C,
        ];
    }

    public static function a()
    {
        return (new static())->withValue(self::EXAMPLE_A);
    }

    public static function b()
    {
        return (new static())->withValue(self::EXAMPLE_B);
    }

    public function getProtectedIndex(): int
    {
        return $this->getIndex();
    }

    public function getProtectedValue(): string
    {
        return $this->getValue();
    }

    public function withProtectedSelection(int $selection): static
    {
        return $this->withSelection($selection);
    }

    public function withProtectedValue(string $value): static
    {
        return $this->withValue($value);
    }

    public static function getProtectedDefaultSelection(): ?int
    {
        return self::getDefaultSelection();
    }
}

class ExampleEnumerationWithDefault extends Enumeration implements XdrEnum
{
    public const EXAMPLE_A = 'exampleA';
    public const EXAMPLE_B = 'exampleB';
    public const EXAMPLE_C = 'exampleC';

    public static function getOptions(): array
    {
        return [
            0 => self::EXAMPLE_A,
            1 => self::EXAMPLE_B,
            2 => self::EXAMPLE_C,
        ];
    }

    public static function getProtectedDefaultSelection(): ?int
    {
        return self::getDefaultSelection();
    }

    protected static function getDefaultSelection(): ?int
    {
        return 1;
    }
}
