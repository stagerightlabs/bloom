<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class MessageType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const ERROR_MSG = 'errorMsg';
    public const AUTH = 'auth';
    public const DONT_HAVE = 'dontHave';
    public const GET_PEERS = 'getPeers';  // get the list of known peers
    public const PEERS = 'peers';
    public const GET_TX_SET = 'getTxSet'; // get a particular txset by hash
    public const TX_SET = 'txSet';
    public const GENERALIZED_TX_SET = 'generalizedTxSet';
    public const TRANSACTION = 'transaction';
    public const GET_SCP_QUORUMSET = 'getScpQuorumset';
    public const SCP_QUORUMSET = 'scpQuorumset';
    public const SCP_MESSAGE = 'scpMessage';
    public const GET_SCP_STATE = 'getScpState';
    public const HELLO = 'hello';
    public const SURVEY_REQUEST = 'surveyRequest';
    public const SURVEY_RESPONSE = 'surveyResponse';
    public const SEND_MORE = 'sendMore';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0  => self::ERROR_MSG,
            2  => self::AUTH,
            3  => self::DONT_HAVE,
            4  => self::GET_PEERS,
            5  => self::PEERS,
            6  => self::GET_TX_SET,
            7  => self::TX_SET,
            8  => self::TRANSACTION,
            9  => self::GET_SCP_QUORUMSET,
            10 => self::SCP_QUORUMSET,
            11 => self::SCP_MESSAGE,
            12 => self::GET_SCP_STATE,
            13 => self::HELLO,
            14 => self::SURVEY_REQUEST,
            15 => self::SURVEY_RESPONSE,
            16 => self::SEND_MORE,
            17 => self::GENERALIZED_TX_SET,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as ERROR_MSG.
     *
     * @return static
     */
    public static function errorMsg(): static
    {
        return (new static())->withValue(self::ERROR_MSG);
    }

    /**
     * Create a new instance pre-selected as AUTH.
     *
     * @return static
     */
    public static function auth(): static
    {
        return (new static())->withValue(self::AUTH);
    }

    /**
     * Create a new instance pre-selected as DONT_HAVE.
     *
     * @return static
     */
    public static function dontHave(): static
    {
        return (new static())->withValue(self::DONT_HAVE);
    }

    /**
     * Create a new instance pre-selected as GET_PEERS.
     *
     * @return static
     */
    public static function getPeers(): static
    {
        return (new static())->withValue(self::GET_PEERS);
    }

    /**
     * Create a new instance pre-selected as PEERS.
     *
     * @return static
     */
    public static function peers(): static
    {
        return (new static())->withValue(self::PEERS);
    }

    /**
     * Create a new instance pre-selected as GET_TX_SET.
     *
     * @return static
     */
    public static function getTxSet(): static
    {
        return (new static())->withValue(self::GET_TX_SET);
    }

    /**
     * Create a new instance pre-selected as TX_SET.
     *
     * @return static
     */
    public static function txSet(): static
    {
        return (new static())->withValue(self::TX_SET);
    }

    /**
     * Create a new instance pre-selected as TRANSACTION.
     *
     * @return static
     */
    public static function transaction(): static
    {
        return (new static())->withValue(self::TRANSACTION);
    }

    /**
     * Create a new instance pre-selected as GET_SCP_QUORUMSET.
     *
     * @return static
     */
    public static function getScpQuorumset(): static
    {
        return (new static())->withValue(self::GET_SCP_QUORUMSET);
    }

    /**
     * Create a new instance pre-selected as SCP_QUORUMSET.
     *
     * @return static
     */
    public static function scpQuorumset(): static
    {
        return (new static())->withValue(self::SCP_QUORUMSET);
    }

    /**
     * Create a new instance pre-selected as SCP_MESSAGE.
     *
     * @return static
     */
    public static function scpMessage(): static
    {
        return (new static())->withValue(self::SCP_MESSAGE);
    }

    /**
     * Create a new instance pre-selected as GET_SCP_STATE.
     *
     * @return static
     */
    public static function getScpState(): static
    {
        return (new static())->withValue(self::GET_SCP_STATE);
    }

    /**
     * Create a new instance pre-selected as HELLO.
     *
     * @return static
     */
    public static function hello(): static
    {
        return (new static())->withValue(self::HELLO);
    }

    /**
     * Create a new instance pre-selected as SURVEY_REQUEST.
     *
     * @return static
     */
    public static function surveyRequest(): static
    {
        return (new static())->withValue(self::SURVEY_REQUEST);
    }

    /**
     * Create a new instance pre-selected as SURVEY_RESPONSE.
     *
     * @return static
     */
    public static function surveyResponse(): static
    {
        return (new static())->withValue(self::SURVEY_RESPONSE);
    }

    /**
     * Create a new instance pre-selected as SEND_MORE.
     *
     * @return static
     */
    public static function sendMore(): static
    {
        return (new static())->withValue(self::SEND_MORE);
    }

    /**
     * Create a new instance pre-selected as GENERALIZED_TX_SET.
     *
     * @return static
     */
    public static function generalizedTxSet(): static
    {
        return (new static())->withValue(self::GENERALIZED_TX_SET);
    }
}
