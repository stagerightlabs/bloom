<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\Signature
 */
class SignatureTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $signature = Signature::of('abcd');

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertEquals('abcd', $signature->getRaw());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $signature = Signature::of('abcd');
        $buffer = XDR::fresh()->write($signature);

        $this->assertEquals('AAAABGFiY2Q=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new Signature());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $signature = XDR::fromBase64('AAAABGFiY2Q=')->read(Signature::class);

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertEquals('abcd', $signature->getRaw());
    }

    /**
     * @test
     * @covers ::getRaw
     * @covers ::withRaw
     */
    public function it_accepts_a_value()
    {
        $signature = (new Signature())->withRaw('abcd');

        $this->assertInstanceOf(Signature::class, $signature);
        $this->assertEquals('abcd', $signature->getRaw());
    }

    /**
     * @test
     * @covers ::withRaw
     */
    public function it_does_not_accept_a_value_longer_than_64_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new Signature())->withRaw(str_repeat('A', 65));
    }
}
