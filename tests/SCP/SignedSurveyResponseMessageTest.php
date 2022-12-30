<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\EncryptedBody;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\SignedSurveyResponseMessage;
use StageRightLabs\Bloom\SCP\SurveyResponseMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SignedSurveyResponseMessage
 */
class SignedSurveyResponseMessageTest extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptedBody(EncryptedBody::of('example'));
        $signedSurveyResponseMessage = (new SignedSurveyResponseMessage())
            ->withSignature(Signature::of('ABCD'))
            ->withResponse($surveyResponseMessage);
        $buffer = XDR::fresh()->write($signedSurveyResponseMessage);

        $this->assertEquals(
            'AAAABEFCQ0QAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAABAAAAAAAAAAdleGFtcGxlAA==',
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
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptedBody(EncryptedBody::of('example'));
        $signedSurveyResponseMessage = (new SignedSurveyResponseMessage())
            ->withResponse($surveyResponseMessage);
        XDR::fresh()->write($signedSurveyResponseMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_response_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $signedSurveyResponseMessage = (new SignedSurveyResponseMessage())
            ->withSignature(Signature::of('ABCD'));
        XDR::fresh()->write($signedSurveyResponseMessage);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $signedSurveyResponseMessage = XDR::fromBase64('AAAABEFCQ0QAAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAAAam1BxzlDU4CGaXl40EB4DWHFzms0sXAq4QQodjODne4AAAABAAAAAAAAAAdleGFtcGxlAA==')
            ->read(SignedSurveyResponseMessage::class);

        $this->assertInstanceOf(SignedSurveyResponseMessage::class, $signedSurveyResponseMessage);
        $this->assertInstanceOf(Signature::class, $signedSurveyResponseMessage->getSignature());
        $this->assertInstanceOf(SurveyResponseMessage::class, $signedSurveyResponseMessage->getResponse());
    }

    /**
     * @test
     * @covers ::withSignature
     * @covers ::getSignature
     */
    public function it_accepts_a_signature()
    {
        $signedSurveyResponseMessage = (new SignedSurveyResponseMessage())
            ->withSignature(Signature::of('ABCD'));

        $this->assertInstanceOf(SignedSurveyResponseMessage::class, $signedSurveyResponseMessage);
        $this->assertInstanceOf(Signature::class, $signedSurveyResponseMessage->getSignature());
    }

    /**
     * @test
     * @covers ::withResponse
     * @covers ::getResponse
     */
    public function it_accepts_a_response()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptedBody(EncryptedBody::of('example'));
        $signedSurveyResponseMessage = (new SignedSurveyResponseMessage())
            ->withResponse($surveyResponseMessage);

        $this->assertInstanceOf(SignedSurveyResponseMessage::class, $signedSurveyResponseMessage);
        $this->assertInstanceOf(SurveyResponseMessage::class, $signedSurveyResponseMessage->getResponse());
    }
}
