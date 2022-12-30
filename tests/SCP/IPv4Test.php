<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\SCP\IPv4;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\IPv4
 */
class IPv4Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_helper()
    {
        $ipv4 = IPv4::of('1.1.1.1');
        $this->assertInstanceOf(IPv4::class, $ipv4);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_invalid_ip_address_strings()
    {
        $this->expectException(InvalidArgumentException::class);
        IPv4::of('abcd');
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ipv4 = IPv4::of('1.1.1.1');
        $buffer = XDR::fresh()->write($ipv4);

        $this->assertEquals('AQEBAQ==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ipv4 = XDR::fromBase64('AQEBAQ==')->read(IPv4::class);

        $this->assertInstanceOf(IPv4::class, $ipv4);
        $this->assertEquals('1.1.1.1', $ipv4->getAddress());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_address_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new IPv4());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     * @covers ::getAddress
     */
    public function it_accepts_a_raw_value()
    {
        $ipv4 = (new IPv4())->withRaw(hex2bin('01010101'));

        $this->assertInstanceOf(IPv4::class, $ipv4);
        $this->assertEquals(hex2bin('01010101'), $ipv4->getRaw());
        $this->assertEquals('1.1.1.1', $ipv4->getAddress());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_a_value_longer_than_four_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new IPv4())->withRaw(hex2bin('0101010102'));
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_a_value_less_than_four_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new IPv4())->withRaw(hex2bin('010101'));
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_will_not_decode_invalid_addresses()
    {
        $this->expectException(UnexpectedValueException::class);
        $ipv4 = new IPv4();
        $ipv4->value = hex2bin('aabbcc');
        $ipv4->getAddress();
    }
}
