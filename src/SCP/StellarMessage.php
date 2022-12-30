<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Transaction\GeneralizedTransactionSet;
use StageRightLabs\Bloom\Transaction\TransactionSet;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class StellarMessage extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return MessageType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            MessageType::ERROR_MSG          => Error::class,
            MessageType::HELLO              => Hello::class,
            MessageType::AUTH               => Auth::class,
            MessageType::DONT_HAVE          => DontHave::class,
            MessageType::PEERS              => PeerAddressList::class,
            MessageType::GET_TX_SET         => UInt256::class,
            MessageType::TX_SET             => TransactionSet::class,
            MessageType::GENERALIZED_TX_SET => GeneralizedTransactionSet::class,
            MessageType::TRANSACTION        => TransactionEnvelope::class,
            MessageType::SURVEY_REQUEST     => SignedSurveyRequestMessage::class,
            MessageType::SURVEY_RESPONSE    => SignedSurveyResponseMessage::class,
            MessageType::GET_SCP_QUORUMSET  => UInt256::class,
            MessageType::SCP_QUORUMSET      => ScpQuorumSet::class,
            MessageType::SCP_MESSAGE        => ScpEnvelope::class,
            MessageType::GET_SCP_STATE      => UInt32::class, // Ledger Sequence Number
            MessageType::SEND_MORE          => SendMore::class,
        ];
    }

    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof MessageType) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Create a new instance by wrapping an error message.
     *
     * @param Error $error
     * @return static
     */
    public static function wrapErrorMessage(Error $error): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::errorMsg();
        $stellarMessage->value = $error;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a 'hello' message.
     *
     * @param Hello $hello
     * @return static
     */
    public static function wrapHelloMessage(Hello $hello): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::hello();
        $stellarMessage->value = $hello;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping an auth message.
     *
     * @param Auth $auth
     * @return static
     */
    public static function wrapAuthMessage(Auth $auth): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::auth();
        $stellarMessage->value = $auth;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a 'dont have' message.
     *
     * @param DontHave $dontHave
     * @return static
     */
    public static function wrapDontHaveMessage(DontHave $dontHave): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::dontHave();
        $stellarMessage->value = $dontHave;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a peer address list.
     *
     * @param PeerAddressList $peers
     * @return static
     */
    public static function wrapPeersMessage(PeerAddressList $peers): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::peers();
        $stellarMessage->value = $peers;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a 'get tx set' message.
     *
     * @param UInt256 $getTxSet
     * @return static
     */
    public static function wrapGetTxSetMessage(UInt256 $getTxSet): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::getTxSet();
        $stellarMessage->value = $getTxSet;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a transaction set.
     *
     * @param TransactionSet $transactionSet
     * @return static
     */
    public static function wrapTransactionSetMessage(TransactionSet $transactionSet): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::txSet();
        $stellarMessage->value = $transactionSet;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a generalized transaction set.
     *
     * @param GeneralizedTransactionSet $generalizedTransactionSet
     * @return static
     */
    public static function wrapGeneralizedTransactionSetMessage(GeneralizedTransactionSet $generalizedTransactionSet): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::generalizedTxSet();
        $stellarMessage->value = $generalizedTransactionSet;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a transaction envelope.
     *
     * @param TransactionEnvelope $envelope
     * @return static
     */
    public static function wrapTransactionMessage(TransactionEnvelope $envelope): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::transaction();
        $stellarMessage->value = $envelope;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a signed survey request message.
     *
     * @param SignedSurveyRequestMessage $request
     * @return static
     */
    public static function wrapSurveyRequestMessage(SignedSurveyRequestMessage $request): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::surveyRequest();
        $stellarMessage->value = $request;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a signed survey response message.
     *
     * @param SignedSurveyResponseMessage $response
     * @return static
     */
    public static function wrapSurveyResponseMessage(SignedSurveyResponseMessage $response): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::surveyResponse();
        $stellarMessage->value = $response;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a 'get scp quorumset' message.
     *
     * @param UInt256 $getScpQuorumset
     * @return static
     */
    public static function wrapGetScpQuorumsetMessage(UInt256 $getScpQuorumset): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::getScpQuorumset();
        $stellarMessage->value = $getScpQuorumset;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a SCP quorumset.
     *
     * @param ScpQuorumSet $scpQuorumset
     * @return static
     */
    public static function wrapScpQuorumsetMessage(ScpQuorumSet $scpQuorumset): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::scpQuorumset();
        $stellarMessage->value = $scpQuorumset;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping an SCP message.
     *
     * @param ScpEnvelope $scpEnvelope
     * @return static
     */
    public static function wrapScpMessage(ScpEnvelope $scpEnvelope): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::scpMessage();
        $stellarMessage->value = $scpEnvelope;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a ledger sequence number.
     *
     * @param UInt32|int $ledgerSeq
     * @return static
     */
    public static function wrapGetScpStateMessage(UInt32|int $ledgerSeq): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::getScpState();
        $stellarMessage->value = is_int($ledgerSeq)
            ? UInt32::of($ledgerSeq)
            : $ledgerSeq;

        return $stellarMessage;
    }

    /**
     * Create a new instance by wrapping a 'send more' message.
     *
     * @param SendMore $sendMore
     * @return static
     */
    public static function wrapSendMoreMessage(SendMore $sendMore): static
    {
        $stellarMessage = new static();
        $stellarMessage->discriminator = MessageType::sendMore();
        $stellarMessage->value = $sendMore;

        return $stellarMessage;
    }

    /**
     * Return the underlying value.
     *
     * @return Error|Hello|Auth|DontHave|PeerAddressList|UInt256|TransactionSet|TransactionEnvelope|SignedSurveyRequestMessage|SignedSurveyResponseMessage|ScpQuorumSet|ScpEnvelope|UInt32|SendMore|null
     */
    public function unwrap(): Error|Hello|Auth|DontHave|PeerAddressList|UInt256|TransactionSet|GeneralizedTransactionSet|TransactionEnvelope|SignedSurveyRequestMessage|SignedSurveyResponseMessage|ScpQuorumSet|ScpEnvelope|UInt32|SendMore|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
