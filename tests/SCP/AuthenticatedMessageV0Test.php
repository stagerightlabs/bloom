<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\HmacSha256Mac;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\SCP\Auth;
use StageRightLabs\Bloom\SCP\AuthenticatedMessageV0;
use StageRightLabs\Bloom\SCP\StellarMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\AuthenticatedMessageV0
 */
class AuthenticatedMessageV0Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withSequence(UInt64::of(1))
            ->withMessage(StellarMessage::wrapAuthMessage(new Auth()))
            ->withMac(HmacSha256Mac::of('example'));
        $buffer = XDR::fresh()->write($authenticatedMessageV0);

        $this->assertEquals(
            'AAAAAAAAAAEAAAACAAAAAGV4YW1wbGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_sequence_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withMessage(StellarMessage::wrapAuthMessage(new Auth()))
            ->withMac(HmacSha256Mac::of('example'));
        XDR::fresh()->write($authenticatedMessageV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_message_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withSequence(UInt64::of(1))
            ->withMac(HmacSha256Mac::of('example'));
        XDR::fresh()->write($authenticatedMessageV0);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_mac_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withSequence(UInt64::of(1))
            ->withMessage(StellarMessage::wrapAuthMessage(new Auth()));
        XDR::fresh()->write($authenticatedMessageV0);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $authenticatedMessageV0 = XDR::fromBase64('AAAAAAAAAAEAAAACAAAAAGV4YW1wbGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
            ->read(AuthenticatedMessageV0::class);

        $this->assertInstanceOf(AuthenticatedMessageV0::class, $authenticatedMessageV0);
        $this->assertInstanceOf(UInt64::class, $authenticatedMessageV0->getSequence());
        $this->assertInstanceOf(StellarMessage::class, $authenticatedMessageV0->getMessage());
        $this->assertInstanceOf(HmacSha256Mac::class, $authenticatedMessageV0->getMac());
    }

    /**
     * @test
     * @covers ::withSequence
     * @covers ::getSequence
     */
    public function it_accepts_a_sequence()
    {
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withSequence(UInt64::of(1));

        $this->assertInstanceOf(AuthenticatedMessageV0::class, $authenticatedMessageV0);
        $this->assertInstanceOf(UInt64::class, $authenticatedMessageV0->getSequence());
    }

    /**
     * @test
     * @covers ::withMessage
     * @covers ::getMessage
     */
    public function it_accepts_a_message()
    {
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withMessage(StellarMessage::wrapAuthMessage(new Auth()));

        $this->assertInstanceOf(AuthenticatedMessageV0::class, $authenticatedMessageV0);
        $this->assertInstanceOf(StellarMessage::class, $authenticatedMessageV0->getMessage());
    }

    /**
     * @test
     * @covers ::withMac
     * @covers ::getMac
     *
     */
    public function it_accepts_a_mac_value()
    {
        $authenticatedMessageV0 = (new AuthenticatedMessageV0())
            ->withMac(HmacSha256Mac::of('example'));

        $this->assertInstanceOf(AuthenticatedMessageV0::class, $authenticatedMessageV0);
        $this->assertInstanceOf(HmacSha256Mac::class, $authenticatedMessageV0->getMac());
    }
}
