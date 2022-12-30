<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Cryptography;

use StageRightLabs\Bloom\Cryptography\HmacSha256Mac;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\HmacSha256Mac
 */
class HmacSha256MacTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_helper()
    {
        $hmacSha256Mac = HmacSha256Mac::of('example');
        $this->assertInstanceOf(HmacSha256Mac::class, $hmacSha256Mac);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $hmacSha256Mac = HmacSha256Mac::of('example');
        $buffer = XDR::fresh()->write($hmacSha256Mac);

        $this->assertEquals('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_mac_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new HmacSha256Mac());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hmacSha256Mac = XDR::fromBase64('ZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=')
            ->read(HmacSha256Mac::class);

        $this->assertInstanceOf(HmacSha256Mac::class, $hmacSha256Mac);
        $this->assertEquals('example', $hmacSha256Mac->getMac());
    }

    /**
     * @test
     * @covers ::withMac
     * @covers ::getMac
     */
    public function it_accepts_a_mac_value()
    {
        $hmacSha256Mac = (new HmacSha256Mac())->withMac('example');

        $this->assertInstanceOf(HmacSha256Mac::class, $hmacSha256Mac);
        $this->assertEquals('example', $hmacSha256Mac->getMac());
    }

    /**
     * @test
     * @covers ::withMac
     */
    public function it_rejects_values_longer_than_32_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new HmacSha256Mac())->withMac(str_repeat('A', 33));
    }
}
