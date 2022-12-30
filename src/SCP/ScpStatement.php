<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpStatement implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected NodeId $nodeId;
    protected UInt64 $slotIndex;
    protected ScpStatementPledges $pledges;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->nodeId)) {
            throw new InvalidArgumentException('The SCP statement is missing a node Id');
        }

        if (!isset($this->slotIndex)) {
            throw new InvalidArgumentException('The SCP statement is missing a slot index');
        }

        if (!isset($this->pledges)) {
            throw new InvalidArgumentException('The SCP statement is missing pledges');
        }

        $xdr->write($this->nodeId)
            ->write($this->slotIndex)
            ->write($this->pledges);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpStatement = new static();
        $scpStatement->nodeId = $xdr->read(NodeId::class);
        $scpStatement->slotIndex = $xdr->read(UInt64::class);
        $scpStatement->pledges = $xdr->read(ScpStatementPledges::class);

        return $scpStatement;
    }

    /**
     * Get the node Id.
     *
     * @return NodeId
     */
    public function getNodeId(): NodeId
    {
        return $this->nodeId;
    }

    /**
     * Accept a node Id.
     *
     * @param NodeId $nodeId
     * @return static
     */
    public function withNodeId(NodeId $nodeId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nodeId = Copy::deep($nodeId);

        return $clone;
    }

    /**
     * Get the slot index.
     *
     * @return UInt64
     */
    public function getSlotIndex(): UInt64
    {
        return $this->slotIndex;
    }

    /**
     * Accept a slot index.
     *
     * @param UInt64 $slotIndex
     * @return static
     */
    public function withSlotIndex(UInt64 $slotIndex): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->slotIndex = Copy::deep($slotIndex);

        return $clone;
    }

    /**
     * Get the pledges.
     *
     * @return ScpStatementPledges
     */
    public function getPledges(): ScpStatementPledges
    {
        return $this->pledges;
    }

    /**
     * Accept pledges.
     *
     * @param ScpStatementPledges $pledges
     * @return static
     */
    public function withPledges(ScpStatementPledges $pledges): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->pledges = Copy::deep($pledges);

        return $clone;
    }
}
