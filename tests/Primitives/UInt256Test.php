<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Primitives\UInt256
 */
class UInt256Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     * @covers ::withBytes
     */
    public function it_instantiates_from_a_string_of_bytes()
    {
        $integer = UInt256::of('abcdefg');

        $this->assertInstanceOf(UInt256::class, $integer);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_strings_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        $integer = UInt256::of(str_repeat('A', 33));

        $this->assertInstanceOf(UInt256::class, $integer);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $integer = UInt256::of('abcdefg');
        $buffer = XDR::fresh()->write($integer)->toBase64();

        $this->assertEquals('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGFiY2RlZmc=', $buffer);
    }

    /**
     * @test
     * @covers ::newFromXdr
     * @covers ::getBytes
     */
    public function it_can_be_read_from_xdr()
    {
        $bytes = UInt256::of('abcdefg')->getBytes();
        $integer = XDR::fromBase64('AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAGFiY2RlZmc=')->read(UInt256::class);

        $this->assertEquals($bytes, $integer->getBytes());
    }
}
