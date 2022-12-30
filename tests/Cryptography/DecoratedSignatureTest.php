<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Account;

use StageRightLabs\Bloom\Cryptography\DecoratedSignature;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Cryptography\SignatureHint;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Cryptography\DecoratedSignature
 */
class DecoratedSignatureTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $decoratedSignature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'))
            ->withSignature(Signature::of('signature'));
        $buffer = XDR::fresh()->write($decoratedSignature);

        $this->assertEquals('aGludAAAAAlzaWduYXR1cmUAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_hint_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $decoratedSignature = (new DecoratedSignature())
            ->withHint(SignatureHint::of('hint'));
        XDR::fresh()->write($decoratedSignature);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $decoratedSignature = (new DecoratedSignature())
            ->withSignature(Signature::of('signature'));
        XDR::fresh()->write($decoratedSignature);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $decoratedSignature = XDR::fromBase64('aGludAAAAAlzaWduYXR1cmUAAAA=')
            ->read(DecoratedSignature::class);

        $this->assertInstanceOf(DecoratedSignature::class, $decoratedSignature);
        $this->assertEquals('hint', $decoratedSignature->getHint()->getValue());
        $this->assertEquals('signature', $decoratedSignature->getSignature()->getRaw());
    }

    /**
     * @test
     * @covers ::getHint
     * @covers ::withHint
     */
    public function it_accepts_a_signature_hint()
    {
        $decoratedSignature = (new DecoratedSignature())->withHint(SignatureHint::of('abcd'));

        $this->assertInstanceOf(DecoratedSignature::class, $decoratedSignature);
        $this->assertInstanceOf(SignatureHint::class, $decoratedSignature->getHint());
    }

    /**
     * @test
     * @covers ::getSignature
     * @covers ::withSignature
     */
    public function it_accepts_a_signature()
    {
        $decoratedSignature = (new DecoratedSignature())->withSignature(Signature::of('abcd'));

        $this->assertInstanceOf(DecoratedSignature::class, $decoratedSignature);
        $this->assertInstanceOf(Signature::class, $decoratedSignature->getSignature());
    }
}
