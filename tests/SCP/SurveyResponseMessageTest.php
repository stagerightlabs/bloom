<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\EncryptedBody;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\SurveyMessageCommandType;
use StageRightLabs\Bloom\SCP\SurveyResponseMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SurveyResponseMessage
 */
class SurveyResponseMessageTest extends TestCase
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
        $buffer = XDR::fresh()->write($surveyResponseMessage);

        $this->assertEquals(
            'AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAQAAAAAAAAAHZXhhbXBsZQA=',
            $buffer->toBase64()
        );
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_surveyor_peer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptedBody(EncryptedBody::of('example'));
        XDR::fresh()->write($surveyResponseMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_surveyed_peer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptedBody(EncryptedBody::of('example'));
        XDR::fresh()->write($surveyResponseMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withEncryptedBody(EncryptedBody::of('example'));
        XDR::fresh()->write($surveyResponseMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_encrypted_body_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1));
        XDR::fresh()->write($surveyResponseMessage);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $surveyResponseMessage = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAQAAAAAAAAAHZXhhbXBsZQA=')
            ->read(SurveyResponseMessage::class);

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(NodeId::class, $surveyResponseMessage->getSurveyorPeerId());
        $this->assertInstanceOf(NodeId::class, $surveyResponseMessage->getSurveyedPeerId());
        $this->assertInstanceOf(UInt32::class, $surveyResponseMessage->getLedgerNumber());
        $this->assertInstanceOf(SurveyMessageCommandType::class, $surveyResponseMessage->getCommandType());
        $this->assertInstanceOf(EncryptedBody::class, $surveyResponseMessage->getEncryptedBody());
    }

    /**
     * @test
     * @covers ::withSurveyorPeerId
     * @covers ::getSurveyorPeerId
     */
    public function it_accepts_a_surveyor_peer_id()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(NodeId::class, $surveyResponseMessage->getSurveyorPeerId());
    }

    /**
     * @test
     * @covers ::withSurveyedPeerId
     * @covers ::getSurveyedPeerId
     */
    public function it_accepts_a_surveyed_peer_id()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(NodeId::class, $surveyResponseMessage->getSurveyedPeerId());
    }

    /**
     * @test
     * @covers ::withLedgerNumber
     * @covers ::getLedgerNumber
     */
    public function it_accepts_a_uint32_as_a_ledger_number()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withLedgerNumber(UInt32::of(1));

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(UInt32::class, $surveyResponseMessage->getLedgerNumber());
    }

    /**
     * @test
     * @covers ::withLedgerNumber
     * @covers ::getLedgerNumber
     */
    public function it_accepts_a_native_int_as_a_ledger_number()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withLedgerNumber(1);

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(UInt32::class, $surveyResponseMessage->getLedgerNumber());
    }

    /**
     * @test
     * @covers ::withCommandType
     * @covers ::getCommandType
     */
    public function it_accepts_a_command_type()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withCommandType(SurveyMessageCommandType::surveyTopology());

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(SurveyMessageCommandType::class, $surveyResponseMessage->getCommandType());
    }

    /**
     * @test
     * @covers ::withEncryptedBody
     * @covers ::getEncryptedBody
     */
    public function it_accepts_an_encrypted_body_as_a_encrypted_body()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withEncryptedBody(EncryptedBody::of('example'));

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(EncryptedBody::class, $surveyResponseMessage->getEncryptedBody());
    }

    /**
     * @test
     * @covers ::withEncryptedBody
     * @covers ::getEncryptedBody
     */
    public function it_accepts_a_native_string_as_a_encrypted_body()
    {
        $surveyResponseMessage = (new SurveyResponseMessage())
            ->withEncryptedBody('example');

        $this->assertInstanceOf(SurveyResponseMessage::class, $surveyResponseMessage);
        $this->assertInstanceOf(EncryptedBody::class, $surveyResponseMessage->getEncryptedBody());
    }
}
