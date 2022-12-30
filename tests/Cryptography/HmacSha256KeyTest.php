<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\HmacSha256Key;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\HmacSha256Key
 */
class HmacSha256KeyTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_helper()
    {
        $hmacSha256Key = HmacSha256Key::of('example');
        $this->assertInstanceOf(HmacSha256Key::class, $hmacSha256Key);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $hmacSha256Key = HmacSha256Key::of('example');
        $buffer = XDR::fresh()->write($hmacSha256Key);

        $this->assertEquals('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_mac_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new HmacSha256Key());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hmacSha256Key = XDR::fromBase64('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=')
            ->read(HmacSha256Key::class);

        $this->assertInstanceOf(HmacSha256Key::class, $hmacSha256Key);
        $this->assertEquals('example', $hmacSha256Key->getKey());
    }

    /**
     * @test
     * @covers ::withKey
     * @covers ::getKey
     */
    public function it_accepts_a_mac_value()
    {
        $hmacSha256Key = (new HmacSha256Key())->withKey('example');

        $this->assertInstanceOf(HmacSha256Key::class, $hmacSha256Key);
        $this->assertEquals('example', $hmacSha256Key->getKey());
    }

    /**
     * @test
     * @covers ::withKey
     */
    public function it_rejects_values_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new HmacSha256Key())->withKey(str_repeat('A', 33));
    }
}
