<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\SCP\SurveyMessageCommandType;
use StageRightLabs\Bloom\SCP\SurveyRequestMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\SurveyRequestMessage
 */
class SurveyRequestMessageTest extends TestCase
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
        $buffer = XDR::fresh()->write($surveyRequestMessage);

        $this->assertEquals(
            'AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAWV4YW1wbGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==',
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
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptionKey(Curve25519Public::of('example'));
        XDR::fresh()->write($surveyRequestMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_surveyed_peer_id_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1))
            ->withEncryptionKey(Curve25519Public::of('example'));
        XDR::fresh()->write($surveyRequestMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_ledger_number_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withEncryptionKey(Curve25519Public::of('example'));
        XDR::fresh()->write($surveyRequestMessage);
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function an_encryption_key_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'))
            ->withLedgerNumber(UInt32::of(1));
        XDR::fresh()->write($surveyRequestMessage);
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $surveyRequestMessage = XDR::fromBase64('AAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAGptQcc5Q1OAhml5eNBAeA1hxc5rNLFwKuEEKHYzg53uAAAAAWV4YW1wbGUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==')
            ->read(SurveyRequestMessage::class);

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(NodeId::class, $surveyRequestMessage->getSurveyorPeerId());
        $this->assertInstanceOf(NodeId::class, $surveyRequestMessage->getSurveyedPeerId());
        $this->assertInstanceOf(UInt32::class, $surveyRequestMessage->getLedgerNumber());
        $this->assertInstanceOf(Curve25519Public::class, $surveyRequestMessage->getEncryptionKey());
        $this->assertInstanceOf(SurveyMessageCommandType::class, $surveyRequestMessage->getCommandType());
    }

    /**
     * @test
     * @covers ::withSurveyorPeerId
     * @covers ::getSurveyorPeerId
     */
    public function it_accepts_a_surveyor_peer_id()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyorPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(NodeId::class, $surveyRequestMessage->getSurveyorPeerId());
    }

    /**
     * @test
     * @covers ::withSurveyedPeerId
     * @covers ::getSurveyedPeerId
     */
    public function it_accepts_a_surveyed_peer_id()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withSurveyedPeerId(NodeId::fromAddressable('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW'));

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(NodeId::class, $surveyRequestMessage->getSurveyedPeerId());
    }

    /**
     * @test
     * @covers ::withLedgerNumber
     * @covers ::getLedgerNumber
     */
    public function it_accepts_a_uint32_as_a_ledger_number()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withLedgerNumber(UInt32::of(1));

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(UInt32::class, $surveyRequestMessage->getLedgerNumber());
    }

    /**
     * @test
     * @covers ::withLedgerNumber
     * @covers ::getLedgerNumber
     */
    public function it_accepts_a_native_int_as_a_ledger_number()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withLedgerNumber(1);

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(UInt32::class, $surveyRequestMessage->getLedgerNumber());
    }

    /**
     * @test
     * @covers ::withEncryptionKey
     * @covers ::getEncryptionKey
     */
    public function it_accepts_an_encryption_key()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withEncryptionKey(Curve25519Public::of('example'));

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(Curve25519Public::class, $surveyRequestMessage->getEncryptionKey());
    }

    /**
     * @test
     * @covers ::withCommandType
     * @covers ::getCommandType
     */
    public function it_accepts_a_command_type()
    {
        $surveyRequestMessage = (new SurveyRequestMessage())
            ->withCommandType(SurveyMessageCommandType::surveyTopology());

        $this->assertInstanceOf(SurveyRequestMessage::class, $surveyRequestMessage);
        $this->assertInstanceOf(SurveyMessageCommandType::class, $surveyRequestMessage->getCommandType());
    }
}
