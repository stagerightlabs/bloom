<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\SCP\IPv6;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\IPv6
 */
class IPv6Test extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_via_static_helper()
    {
        $ipv6 = IPv6::of('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $this->assertInstanceOf(IPv6::class, $ipv6);
    }

    /**
     * @test
     * @covers ::of
     */
    public function it_rejects_invalid_ip_address_strings()
    {
        $this->expectException(InvalidArgumentException::class);
        IPv6::of('abcd');
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $ipv6 = IPv6::of('2001:0db8:85a3:0000:0000:8a2e:0370:7334');
        $buffer = XDR::fresh()->write($ipv6);

        $this->assertEquals('IAENuIWjAAAAAIouA3BzNA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $ipv6 = XDR::fromBase64('IAENuIWjAAAAAIouA3BzNA==')->read(IPv6::class);

        $this->assertInstanceOf(IPv6::class, $ipv6);
        $this->assertEquals('2001:db8:85a3::8a2e:370:7334', $ipv6->getAddress());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_address_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new IPv6());
    }

    /**
     * @test
     * @covers ::withRaw
     * @covers ::getRaw
     * @covers ::getAddress
     */
    public function it_accepts_a_raw_value()
    {
        $ipv6 = (new IPv6())->withRaw(hex2bin('20010db885a3000000008a2e03707334'));

        $this->assertInstanceOf(IPv6::class, $ipv6);
        $this->assertEquals(hex2bin('20010db885a3000000008a2e03707334'), $ipv6->getRaw());
        $this->assertEquals('2001:db8:85a3::8a2e:370:7334', $ipv6->getAddress());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_a_value_longer_than_sixteen_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new IPv6())->withRaw(hex2bin('20010db885a3000000008a2e03707334aa'));
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_will_not_accept_a_value_less_than_sixteen_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new IPv6())->withRaw(hex2bin('20010db885a3'));
    }

    /**
     * @test
     * @covers ::getAddress
     */
    public function it_will_not_decode_invalid_addresses()
    {
        $this->expectException(UnexpectedValueException::class);
        $ipv6 = new IPv6();
        $ipv6->value = hex2bin('aabbcc');
        $ipv6->getAddress();
    }
}
