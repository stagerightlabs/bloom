<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SurveyResponseMessage implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected NodeId $surveyorPeerId;
    protected NodeId $surveyedPeerId;
    protected UInt32 $ledgerNum;
    protected SurveyMessageCommandType $commandType;
    protected EncryptedBody $encryptedBody;

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
            throw new InvalidArgumentException('The survey response message is missing a surveyor peer Id');
        }

        if (!isset($this->surveyedPeerId)) {
            throw new InvalidArgumentException('The survey response message is missing a surveyed peer Id');
        }

        if (!isset($this->ledgerNum)) {
            throw new InvalidArgumentException('The survey response message is missing a ledger number');
        }

        if (!isset($this->commandType)) {
            $this->commandType = SurveyMessageCommandType::surveyTopology();
        }

        if (!isset($this->encryptedBody)) {
            throw new InvalidArgumentException('The survey response message is missing an encrypted body');
        }

        $xdr->write($this->surveyorPeerId)
            ->write($this->surveyedPeerId)
            ->write($this->ledgerNum)
            ->write($this->commandType)
            ->write($this->encryptedBody);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $surveyResponseMessage = new static();
        $surveyResponseMessage->surveyorPeerId = $xdr->read(NodeId::class);
        $surveyResponseMessage->surveyedPeerId = $xdr->read(NodeId::class);
        $surveyResponseMessage->ledgerNum = $xdr->read(UInt32::class);
        $surveyResponseMessage->commandType = $xdr->read(SurveyMessageCommandType::class);
        $surveyResponseMessage->encryptedBody = $xdr->read(EncryptedBody::class);

        return $surveyResponseMessage;
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

    /**
     * Get the encrypted body.
     *
     * @return EncryptedBody
     */
    public function getEncryptedBody(): EncryptedBody
    {
        return $this->encryptedBody;
    }

    /**
     * Accept an encrypted body.
     *
     * @param EncryptedBody|string $encryptedBody
     * @return static
     */
    public function withEncryptedBody(EncryptedBody|string $encryptedBody): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->encryptedBody = is_string($encryptedBody)
            ? EncryptedBody::of($encryptedBody)
            : Copy::deep($encryptedBody);

        return $clone;
    }
}
