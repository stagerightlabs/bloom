<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\Curve25519Public
 */
class Curve25519PublicTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_via_static_helper()
    {
        $curve25519Public = Curve25519Public::of('example');
        $this->assertInstanceOf(Curve25519Public::class, $curve25519Public);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $curve25519Public = Curve25519Public::of('example');
        $buffer = XDR::fresh()->write($curve25519Public);

        $this->assertEquals('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new Curve25519Public());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $curve25519Public = XDR::fromBase64('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=')
            ->read(Curve25519Public::class);

        $this->assertInstanceOf(Curve25519Public::class, $curve25519Public);
        $this->assertEquals('example', $curve25519Public->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     */
    public function it_accepts_a_raw_value()
    {
        $curve25519Public = (new Curve25519Public())->withRaw('example');

        $this->assertInstanceOf(Curve25519Public::class, $curve25519Public);
        $this->assertEquals('example', $curve25519Public->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_rejects_raw_values_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Curve25519Public())->withRaw(str_repeat('A', 33));
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_converted_to_a_native_string()
    {
        $this->assertEquals('example', Curve25519Public::of('example')->toNativeString());
    }
}
