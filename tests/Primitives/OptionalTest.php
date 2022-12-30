<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Optional
 */
class OptionalTest extends TestCase
{
    /**
     * @test
     * @covers ::none
     * @covers ::hasValue
     * @covers ::hasValueForXdr
     */
    public function it_can_determine_if_a_value_is_present()
    {
        $optional = ExampleOptional::none();
        $this->assertFalse($optional->hasValueForXdr());

        $optional = ExampleOptional::some(UInt32::of(1));
        $this->assertTrue($optional->hasValueForXdr());
    }

    /**
     * @test
     * @covers ::hasValue
     */
    public function it_knows_when_it_does_not_have_a_value()
    {
        $optional = ExampleOptional::none();
        $this->assertFalse($optional->hasProtectedValue());

        $optional = ExampleOptional::some(UInt32::of(1));
        $this->assertTrue($optional->hasProtectedValue());
    }

    /**
     * @test
     * @covers ::getValue
     * @covers ::getXdrValue
     * @covers ::getXdrValueLength
     */
    public function it_can_be_converted_to_xdr()
    {
        $optional = ExampleOptional::some(UInt32::of(1));
        $buffer = XDR::fresh()->write($optional)->toBase64();

        $this->assertEquals('AAAAAQAAAAE=', $buffer);
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $optional = XDR::fromBase64('AAAAAQAAAAE=')->read(ExampleOptional::class);

        $this->assertInstanceOf(ExampleOptional::class, $optional);
        $this->assertInstanceOf(UInt32::class, $optional->unwrap());
    }

    /**
     * @test
     * @covers ::withValue
     * @covers ::getValue
     */
    public function it_can_wrap_a_native_type()
    {
        $optional = ExampleNativeOptional::some(5);
        $buffer = XDR::fresh()->write($optional)->toBase64();
        $decoded = XDR::fromBase64('AAAAAQAAAAU=')->read(ExampleNativeOptional::class);

        $this->assertEquals('AAAAAQAAAAU=', $buffer);
        $this->assertInstanceOf(ExampleNativeOptional::class, $decoded);
        $this->assertEquals(5, $optional->unwrap());
        $this->assertEquals(5, $decoded->unwrap());
    }
}

class ExampleOptional extends Optional implements XdrOptional
{
    public static function getXdrValueType(): string
    {
        return UInt32::class;
    }

    public static function some(UInt32 $uint32): static
    {
        return self::none()->withValue($uint32);
    }

    public function hasProtectedValue(): bool
    {
        return $this->hasValue();
    }

    public function hasNoProtectedValue(): bool
    {
        return !$this->hasValue();
    }

    public function unwrap(): UInt32
    {
        return $this->getValue();
    }
}

class ExampleNativeOptional extends Optional implements XdrOptional
{
    public static function getXdrValueType(): string
    {
        return XDR::INT;
    }

    public static function some(int $native): static
    {
        return self::none()->withValue($native);
    }

    public function unwrap(): int
    {
        return $this->getValue();
    }
}
