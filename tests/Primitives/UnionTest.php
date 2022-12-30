<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\Union
 */
class UnionTest extends TestCase
{
    /**
     * @test
     * @covers ::getDiscriminator
     * @covers ::getXdrDiscriminator
     */
    public function it_returns_the_xdr_discriminator()
    {
        $foo = new Foo();
        $union = ExampleUnion::wrap($foo);

        $this->assertInstanceOf(EnumDiscriminator::class, $union->getXdrDiscriminator());
    }

    /**
     * @test
     * @covers ::getXdrArms
     */
    public function it_returns_the_type_map_arms()
    {
        $expected = [
            EnumDiscriminator::ENUM_FOO => Foo::class,
            EnumDiscriminator::ENUM_BAR => Bar::class,
        ];

        $this->assertEquals($expected, ExampleUnion::getXdrArms());
    }

    /**
     * @test
     * @covers ::getXdrDiscriminatedValueType
     * @covers ::getXdrDiscriminatedValueLength
     */
    public function it_returns_a_value_type_for_an_enumeration_discriminator()
    {
        $enum = EnumDiscriminator::foo();

        $this->assertEquals(Foo::class, ExampleUnion::getXdrDiscriminatedValueType($enum));
        $this->assertNull(ExampleUnion::getXdrDiscriminatedValueLength($enum));
    }

    /**
     * @test
     * @covers ::getXdrDiscriminatedValueType
     */
    public function it_returns_a_value_type_for_an_xdr_enum_discriminator()
    {
        $enum = new SimpleDiscriminator(0);
        $this->assertEquals(Foo::class, SimpleUnion::getXdrDiscriminatedValueType($enum));
    }

    /**
     * @test
     * @covers ::getXdrValue
     */
    public function it_can_be_converted_to_xdr()
    {
        $foo = new Foo();
        $union = ExampleUnion::wrap($foo);
        $buffer = XDR::fresh()->write($union)->toBase64();

        $this->assertEquals('AAAAAAAABAA=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $union = XDR::fromBase64('AAAAAAAABAA=')->read(ExampleUnion::class);

        $this->assertInstanceOf(ExampleUnion::class, $union);
        $this->assertInstanceOf(Foo::class, $union->unwrap());
    }
}

class EnumDiscriminator extends Enumeration implements XdrEnum
{
    public int $value = 0;
    public const ENUM_FOO = 'foo';
    public const ENUM_BAR = 'bar';

    public static function getOptions(): array
    {
        return [
            0 => self::ENUM_FOO,
            1 => self::ENUM_BAR,
        ];
    }

    public static function foo()
    {
        return (new static())->withValue(self::ENUM_FOO);
    }

    public static function bar()
    {
        return (new static())->withValue(self::ENUM_BAR);
    }
}

class ExampleUnion extends Union
{
    public static function getXdrDiscriminatorType(): string
    {
        return EnumDiscriminator::class;
    }

    public static function arms(): array
    {
        return [
            EnumDiscriminator::ENUM_FOO => Foo::class,
            EnumDiscriminator::ENUM_BAR => Bar::class,
        ];
    }

    public static function wrap(Foo|Bar $value)
    {
        $union = new ExampleUnion();
        $union->value = $value;
        if ($value instanceof Foo) {
            $union->discriminator = EnumDiscriminator::foo();
        } else {
            $union->discriminator = EnumDiscriminator::bar();
        }

        return $union;
    }

    public function unwrap(): Foo|Bar|null
    {
        return isset($this->value) ? $this->value : null;
    }
}

class SimpleDiscriminator implements XdrEnum
{
    public const SIMPLE_FOO = 0;
    public const SIMPLE_BAR = 1;

    public function __construct(public int $selection)
    {
        $this->selection = $selection;
    }

    public function getXdrSelection(): int
    {
        return $this->selection;
    }

    public static function newFromXdr(int $value): static
    {
        return new static($value);
    }

    public function isValidXdrSelection(int $value): bool
    {
        return in_array($value, [
            self::SIMPLE_FOO,
            self::SIMPLE_BAR
        ], true);
    }
}

class SimpleUnion extends Union
{
    public static function getXdrDiscriminatorType(): string
    {
        return SimpleDiscriminator::class;
    }

    protected static function arms(): array
    {
        return [
            SimpleDiscriminator::SIMPLE_FOO => Foo::class,
            SimpleDiscriminator::SIMPLE_BAR => Bar::class,
        ];
    }
}


class Foo implements XdrTypedef
{
    public int $value = 1024;

    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->value, XDR::INT);
    }

    public static function newFromXdr(XDR &$xdr): static
    {
        $bar = new static();
        $bar->value = $xdr->read(XDR::INT);

        return $bar;
    }
}

class Bar implements XdrTypedef
{
    public int $value = 255;

    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->value, XDR::INT);
    }

    public static function newFromXdr(XDR &$xdr): static
    {
        $bar = new static();
        $bar->value = $xdr->read(XDR::INT);

        return $bar;
    }
}
