<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Curve25519Public;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SurveyRequestMessage implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected NodeId $surveyorPeerId;
    protected NodeId $surveyedPeerId;
    protected UInt32 $ledgerNum;
    protected Curve25519Public $encryptionKey;
    protected SurveyMessageCommandType $commandType;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->surveyorPeerId)) {
            throw new InvalidArgumentException('The survey request message is missing a surveyor peer id');
        }

        if (!isset($this->surveyedPeerId)) {
            throw new InvalidArgumentException('The survey request message is missing a surveyed peer id');
        }

        if (!isset($this->ledgerNum)) {
            throw new InvalidArgumentException('The survey request message is missing a ledger number');
        }

        if (!isset($this->encryptionKey)) {
            throw new InvalidArgumentException('The survey request message is missing an encryption key');
        }

        if (!isset($this->commandType)) {
            $this->commandType = SurveyMessageCommandType::surveyTopology();
        }

        $xdr->write($this->surveyorPeerId)
            ->write($this->surveyedPeerId)
            ->write($this->ledgerNum)
            ->write($this->encryptionKey)
            ->write($this->commandType);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $surveyRequestMessage = new static();
        $surveyRequestMessage->surveyorPeerId = $xdr->read(NodeId::class);
        $surveyRequestMessage->surveyedPeerId = $xdr->read(NodeId::class);
        $surveyRequestMessage->ledgerNum = $xdr->read(UInt32::class);
        $surveyRequestMessage->encryptionKey = $xdr->read(Curve25519Public::class);
        $surveyRequestMessage->commandType = $xdr->read(SurveyMessageCommandType::class);

        return $surveyRequestMessage;
    }

    /**
     * Get the surveyor peer Id.
     *
     * @return NodeId
     */
    public function getSurveyorPeerId(): NodeId
    {
        return $this->surveyorPeerId;
    }

    /**
     * Accept a surveyor peer Id.
     *
     * @param NodeId $surveyorPeerId
     * @return static
     */
    public function withSurveyorPeerId(NodeId $surveyorPeerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->surveyorPeerId = Copy::deep($surveyorPeerId);

        return $clone;
    }

    /**
     * Get the surveyed peer Id.
     *
     * @return NodeId
     */
    public function getSurveyedPeerId(): NodeId
    {
        return $this->surveyedPeerId;
    }

    /**
     * Accept a surveyed peer Id.
     *
     * @param NodeId $surveyedPeerId
     * @return static
     */
    public function withSurveyedPeerId(NodeId $surveyedPeerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->surveyedPeerId = Copy::deep($surveyedPeerId);

        return $clone;
    }

    /**
     * Get the ledger number.
     *
     * @return UInt32
     */
    public function getLedgerNumber(): UInt32
    {
        return $this->ledgerNum;
    }

    /**
     * Accept a ledger number.
     *
     * @param UInt32|int $ledgerNum
     * @return static
     */
    public function withLedgerNumber(UInt32|int $ledgerNum): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ledgerNum = is_int($ledgerNum)
            ? UInt32::of($ledgerNum)
            : Copy::deep($ledgerNum);

        return $clone;
    }

    /**
     * Get the encryption key.
     *
     * @return Curve25519Public
     */
    public function getEncryptionKey(): Curve25519Public
    {
        return $this->encryptionKey;
    }

    /**
     * Accept an encryption key.
     *
     * @param Curve25519Public $encryptionKey
     * @return static
     */
    public function withEncryptionKey(Curve25519Public $encryptionKey): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->encryptionKey = Copy::deep($encryptionKey);

        return $clone;
    }

    /**
     * Get the command type.
     *
     * @return SurveyMessageCommandType
     */
    public function getCommandType(): SurveyMessageCommandType
    {
        return $this->commandType;
    }

    /**
     * Accept a command type.
     *
     * @param SurveyMessageCommandType $commandType
     * @return static
     */
    public function withCommandType(SurveyMessageCommandType $commandType): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->commandType = Copy::deep($commandType);

        return $clone;
    }
}
