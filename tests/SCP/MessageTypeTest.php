<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\SCP;

use StageRightLabs\Bloom\SCP\MessageType;
use StageRightLabs\Bloom\Tests\TestCase;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\SCP\MessageType
 */
class MessageTypeTest extends TestCase
{
    /**
     * @test
     * @covers ::getOptions
     */
    public function it_returns_options_as_defined_by_spec()
    {
        $expected = [
            0  => MessageType::ERROR_MSG,
            2  => MessageType::AUTH,
            3  => MessageType::DONT_HAVE,
            4  => MessageType::GET_PEERS,
            5  => MessageType::PEERS,
            6  => MessageType::GET_TX_SET,
            7  => MessageType::TX_SET,
            8  => MessageType::TRANSACTION,
            9  => MessageType::GET_SCP_QUORUMSET,
            10 => MessageType::SCP_QUORUMSET,
            11 => MessageType::SCP_MESSAGE,
            12 => MessageType::GET_SCP_STATE,
            13 => MessageType::HELLO,
            14 => MessageType::SURVEY_REQUEST,
            15 => MessageType::SURVEY_RESPONSE,
            16 => MessageType::SEND_MORE,
            17 => MessageType::GENERALIZED_TX_SET,
        ];
        $messageType = new MessageType();

        $this->assertEquals($expected, $messageType->getOptions());
    }

    /**
     * @test
     * @covers ::errorMsg
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_error_message_type()
    {
        $messageType = MessageType::errorMsg();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::ERROR_MSG, $messageType->getType());
    }

    /**
     * @test
     * @covers ::auth
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_an_auth_type()
    {
        $messageType = MessageType::auth();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::AUTH, $messageType->getType());
    }

    /**
     * @test
     * @covers ::dontHave
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_dont_have_type()
    {
        $messageType = MessageType::dontHave();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::DONT_HAVE, $messageType->getType());
    }

    /**
     * @test
     * @covers ::getPeers
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_get_peers_type()
    {
        $messageType = MessageType::getPeers();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::GET_PEERS, $messageType->getType());
    }

    /**
     * @test
     * @covers ::peers
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_peers_type()
    {
        $messageType = MessageType::peers();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::PEERS, $messageType->getType());
    }

    /**
     * @test
     * @covers ::getTxSet
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_get_tx_set_type()
    {
        $messageType = MessageType::getTxSet();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::GET_TX_SET, $messageType->getType());
    }

    /**
     * @test
     * @covers ::txSet
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_tx_set_type()
    {
        $messageType = MessageType::txSet();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::TX_SET, $messageType->getType());
    }

    /**
     * @test
     * @covers ::transaction
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_transaction_type()
    {
        $messageType = MessageType::transaction();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::TRANSACTION, $messageType->getType());
    }

    /**
     * @test
     * @covers ::getScpQuorumset
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_get_scp_quorumset_type()
    {
        $messageType = MessageType::getScpQuorumset();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::GET_SCP_QUORUMSET, $messageType->getType());
    }

    /**
     * @test
     * @covers ::scpQuorumset
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_scp_quorumset_type()
    {
        $messageType = MessageType::scpQuorumset();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::SCP_QUORUMSET, $messageType->getType());
    }

    /**
     * @test
     * @covers ::scpMessage
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_scp_message_type()
    {
        $messageType = MessageType::scpMessage();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::SCP_MESSAGE, $messageType->getType());
    }

    /**
     * @test
     * @covers ::getScpState
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_get_scp_state_type()
    {
        $messageType = MessageType::getScpState();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::GET_SCP_STATE, $messageType->getType());
    }

    /**
     * @test
     * @covers ::hello
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_hello_type()
    {
        $messageType = MessageType::hello();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::HELLO, $messageType->getType());
    }

    /**
     * @test
     * @covers ::surveyRequest
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_survey_request_type()
    {
        $messageType = MessageType::surveyRequest();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::SURVEY_REQUEST, $messageType->getType());
    }

    /**
     * @test
     * @covers ::surveyResponse
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_survey_response_type()
    {
        $messageType = MessageType::surveyResponse();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::SURVEY_RESPONSE, $messageType->getType());
    }

    /**
     * @test
     * @covers ::sendMore
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_send_more_type()
    {
        $messageType = MessageType::sendMore();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::SEND_MORE, $messageType->getType());
    }

    /**
     * @test
     * @covers ::generalizedTxSet
     * @covers ::getType
     */
    public function it_can_be_instantiated_as_a_generalized_tx_set_type()
    {
        $messageType = MessageType::generalizedTxSet();

        $this->assertInstanceOf(MessageType::class, $messageType);
        $this->assertEquals(MessageType::GENERALIZED_TX_SET, $messageType->getType());
    }
}
