<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\SignedSurveyRequestMessage;
use StageRightLabs\Bloom\SCP\SurveyRequestMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SignedSurveyRequestMessage
 */
class SignedSurveyRequestMessageTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptionKey(Curve25519Public::of('example'));
        $signedSurveyRequestMessage = (new SignedSurveyRequestMessage())
            ->withSignature(Signature::of('abcd'))
            ->withRequest($surveyRequestMessage);
        $buffer = XDR::fresh()->write($signedSurveyRequestMessage);

        $this->assertEquals(
            'AAAABGFiY2QAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAABZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_signature_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptionKey(Curve25519Public::of('example'));
        $signedSurveyRequestMessage = (new SignedSurveyRequestMessage())
            ->withRequest($surveyRequestMessage);
        XDR::fresh()->write($signedSurveyRequestMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_request_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $signedSurveyRequestMessage = (new SignedSurveyRequestMessage())
            ->withSignature(Signature::of('abcd'));
        XDR::fresh()->write($signedSurveyRequestMessage);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $signedSurveyRequestMessage = XDR::fromBase64('AAAABGFiY2QAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAABZXhhbXBsZQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA')
            ->read(SignedSurveyRequestMessage::class);

        $this->assertInstanceOf(SignedSurveyRequestMessage::class, $signedSurveyRequestMessage);
        $this->assertInstanceOf(Signature::class, $signedSurveyRequestMessage->getSignature());
        $this->assertInstanceOf(SurveyRequestMessage::class, $signedSurveyRequestMessage->getRequest());
    }

    /**
     * @test
     * @covers ::withSignature
     * @covers ::getSignature
     */
    public function it_accepts_a_signature()
    {
        $signedSurveyRequestMessage = (new SignedSurveyRequestMessage())
            ->withSignature(Signature::of('ABCD'));

        $this->assertInstanceOf(SignedSurveyRequestMessage::class, $signedSurveyRequestMessage);
        $this->assertInstanceOf(Signature::class, $signedSurveyRequestMessage->getSignature());
    }

    /**
     * @test
     * @covers ::withRequest
     * @covers ::getRequest
     */
    public function it_accepts_a_request()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptionKey(Curve25519Public::of('example'));
        $signedSurveyRequestMessage = (new SignedSurveyRequestMessage())
            ->withRequest($surveyRequestMessage);

        $this->assertInstanceOf(SignedSurveyRequestMessage::class, $signedSurveyRequestMessage);
        $this->assertInstanceOf(SurveyRequestMessage::class, $signedSurveyRequestMessage->getRequest());
    }
}
