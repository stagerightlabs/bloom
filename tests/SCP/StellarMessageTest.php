<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\SCP\Auth;
use StageRightLabs\Bloom\SCP\DontHave;
use StageRightLabs\Bloom\SCP\Error;
use StageRightLabs\Bloom\SCP\Hello;
use StageRightLabs\Bloom\SCP\MessageType;
use StageRightLabs\Bloom\SCP\PeerAddressList;
use StageRightLabs\Bloom\SCP\ScpEnvelope;
use StageRightLabs\Bloom\SCP\ScpQuorumSet;
use StageRightLabs\Bloom\SCP\SendMore;
use StageRightLabs\Bloom\SCP\SignedSurveyRequestMessage;
use StageRightLabs\Bloom\SCP\SignedSurveyResponseMessage;
use StageRightLabs\Bloom\SCP\StellarMessage;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\Bloom\Transaction\TransactionSet;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\StellarMessage
 */
class StellarMessageTest extends TestCase
{
    /**
     * @test
     * @covers ::getXdrDiscriminatorType
     */
    public function it_defines_an_xdr_union_discriminator()
    {
        $this->assertEquals(MessageType::class, StellarMessage::getXdrDiscriminatorType());
    }

    /**
     * @test
     * @covers ::arms
     */
    public function it_returns_arms_as_defined_by_spec()
    {
        $expected = [
            MessageType::ERROR_MSG          => Error::class,
            MessageType::HELLO              => Hello::class,
            MessageType::AUTH               => Auth::class,
            MessageType::DONT_HAVE          => DontHave::class,
            MessageType::PEERS              => PeerAddressList::class,
            MessageType::GET_TX_SET         => UInt256::class,
            MessageType::TX_SET             => TransactionSet::class,
            MessageType::TRANSACTION        => TransactionEnvelope::class,
            MessageType::SURVEY_REQUEST     => SignedSurveyRequestMessage::class,
            MessageType::SURVEY_RESPONSE    => SignedSurveyResponseMessage::class,
            MessageType::GET_SCP_QUORUMSET  => UInt256::class,
            MessageType::SCP_QUORUMSET      => ScpQuorumSet::class,
            MessageType::SCP_MESSAGE        => ScpEnvelope::class,
            MessageType::GET_SCP_STATE      => UInt32::class,
            MessageType::SEND_MORE          => SendMore::class,
            MessageType::GENERALIZED_TX_SET => GeneralizedTransactionSet::class,
        ];

        $this->assertEquals($expected, StellarMessage::arms());
    }

    /**
     * @test
     * @covers ::wrapErrorMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_error_message()
    {
        $stellarMessage = StellarMessage::wrapErrorMessage(new Error());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(Error::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::ERROR_MSG, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapHelloMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_hello_message()
    {
        $stellarMessage = StellarMessage::wrapHelloMessage(new Hello());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(Hello::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::HELLO, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapAuthMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_an_auth_message()
    {
        $stellarMessage = StellarMessage::wrapAuthMessage(new Auth());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(Auth::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::AUTH, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapDontHaveMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_dont_have_message()
    {
        $stellarMessage = StellarMessage::wrapDontHaveMessage(new DontHave());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(DontHave::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::DONT_HAVE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapPeersMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_peers_message()
    {
        $stellarMessage = StellarMessage::wrapPeersMessage(PeerAddressList::empty());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(PeerAddressList::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::PEERS, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapGetTxSetMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_get_tx_set_message()
    {
        $stellarMessage = StellarMessage::wrapGetTxSetMessage(UInt256::of('1'));

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(UInt256::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::GET_TX_SET, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapTransactionSetMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_transaction_set_message()
    {
        $stellarMessage = StellarMessage::wrapTransactionSetMessage(new TransactionSet());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(TransactionSet::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::TX_SET, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapGeneralizedTransactionSetMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_generalized_transaction_set_message()
    {
        $stellarMessage = StellarMessage::wrapGeneralizedTransactionSetMessage(new GeneralizedTransactionSet());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(GeneralizedTransactionSet::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::GENERALIZED_TX_SET, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapTransactionMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_transaction_message()
    {
        $stellarMessage = StellarMessage::wrapTransactionMessage(new TransactionEnvelope());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(TransactionEnvelope::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::TRANSACTION, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapSurveyRequestMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_survey_request_message()
    {
        $stellarMessage = StellarMessage::wrapSurveyRequestMessage(new SignedSurveyRequestMessage());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(SignedSurveyRequestMessage::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::SURVEY_REQUEST, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapSurveyResponseMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_survey_response_message()
    {
        $stellarMessage = StellarMessage::wrapSurveyResponseMessage(new SignedSurveyResponseMessage());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(SignedSurveyResponseMessage::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::SURVEY_RESPONSE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapGetScpQuorumsetMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_get_scp_quorumset_message()
    {
        $stellarMessage = StellarMessage::wrapGetScpQuorumsetMessage(UInt256::of('2'));

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(UInt256::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::GET_SCP_QUORUMSET, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapScpQuorumsetMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_scp_quorumset_message()
    {
        $stellarMessage = StellarMessage::wrapScpQuorumsetMessage(new ScpQuorumSet());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(ScpQuorumSet::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::SCP_QUORUMSET, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapScpMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_scp_message()
    {
        $stellarMessage = StellarMessage::wrapScpMessage(new ScpEnvelope());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(ScpEnvelope::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::SCP_MESSAGE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapGetScpStateMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_uint_32_get_scp_state_message()
    {
        $stellarMessage = StellarMessage::wrapGetScpStateMessage(UInt32::of(3));

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(UInt32::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::GET_SCP_STATE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapGetScpStateMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_native_int_get_scp_state_message()
    {
        $stellarMessage = StellarMessage::wrapGetScpStateMessage(3);

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(UInt32::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::GET_SCP_STATE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::wrapSendMoreMessage
     * @covers ::unwrap
     * @covers ::getType
     */
    public function it_can_wrap_a_send_more_message()
    {
        $stellarMessage = StellarMessage::wrapSendMoreMessage(new SendMore());

        $this->assertInstanceOf(StellarMessage::class, $stellarMessage);
        $this->assertInstanceOf(SendMore::class, $stellarMessage->unwrap());
        $this->assertEquals(MessageType::SEND_MORE, $stellarMessage->getType());
    }

    /**
     * @test
     * @covers ::getType
     */
    public function it_returns_null_if_no_value_is_set()
    {
        $this->assertNull((new StellarMessage())->getType());
    }
}
