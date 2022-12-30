<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\SignatureHint
 */
class SignatureHintTest extends TestCase
{
    /**
     * @test
     * @covers ::of
     */
    public function it_can_be_instantiated_from_a_string()
    {
        $hint = SignatureHint::of('abcd');

        $this->assertInstanceOf(SignatureHint::class, $hint);
        $this->assertEquals('abcd', $hint->getValue());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $hint = SignatureHint::of('abcd');
        $buffer = XDR::fresh()->write($hint);

        $this->assertEquals('YWJjZA==', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_value_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new SignatureHint());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $hint = XDR::fromBase64('YWJjZA==')->read(SignatureHint::class);

        $this->assertInstanceOf(SignatureHint::class, $hint);
        $this->assertEquals('abcd', $hint->getValue());
    }

    /**
     * @test
     * @covers ::getValue
     * @covers ::withValue
     */
    public function it_accepts_a_value()
    {
        $hint = (new SignatureHint())->withValue('abcd');

        $this->assertInstanceOf(SignatureHint::class, $hint);
        $this->assertEquals('abcd', $hint->getValue());
    }

    /**
     * @test
     * @covers ::withValue
     */
    public function it_does_not_accept_a_value_longer_than_4_bytes()
    {
        $this->expectException(InvalidArgumentException::class);
        (new SignatureHint())->withValue(str_repeat('A', 5));
    }
}
