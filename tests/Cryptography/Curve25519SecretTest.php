<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\Curve25519Secret;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\Curve25519Secret
 */
class Curve25519SecretTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_created_via_static_helper()
    {
        $curve25519Secret = Curve25519Secret::of('example');
        $this->assertInstanceOf(Curve25519Secret::class, $curve25519Secret);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $curve25519Secret = Curve25519Secret::of('example');
        $buffer = XDR::fresh()->write($curve25519Secret);

        $this->assertEquals('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new Curve25519Secret());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $curve25519Secret = XDR::fromBase64('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=')
            ->read(Curve25519Secret::class);

        $this->assertInstanceOf(Curve25519Secret::class, $curve25519Secret);
        $this->assertEquals('example', $curve25519Secret->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     */
    public function it_accepts_a_raw_value()
    {
        $curve25519Secret = (new Curve25519Secret())->withRaw('example');

        $this->assertInstanceOf(Curve25519Secret::class, $curve25519Secret);
        $this->assertEquals('example', $curve25519Secret->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_rejects_raw_values_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Curve25519Secret())->withRaw(str_repeat('A', 33));
    }

    /**
     * @test
     * @covers ::__toString
     * @covers ::toNativeString
     */
    public function it_can_be_cast_to_a_native_string()
    {
        $this->assertEquals('example', Curve25519Secret::of('example')->toNativeString());
    }
}
