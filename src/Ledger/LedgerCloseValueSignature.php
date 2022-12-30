<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\SCP\NodeId;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerCloseValueSignature implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected NodeId $nodeId; // The node that introduced the value
    protected Signature $signature; // the node's signature

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->nodeId)) {
            throw new InvalidArgumentException('The ledger close value signature is missing a node Id');
        }

        if (!isset($this->signature)) {
            throw new InvalidArgumentException('The ledger close value signature is missing a signature');
        }

        $xdr->write($this->nodeId)->write($this->signature);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerCloseValueSignature = new static();
        $ledgerCloseValueSignature->nodeId = $xdr->read(NodeId::class);
        $ledgerCloseValueSignature->signature = $xdr->read(Signature::class);

        return $ledgerCloseValueSignature;
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
        $clone = Copy::deep($this);
        $clone->nodeId = Copy::deep($nodeId);

        return $clone;
    }

    /**
     * Get the signature.
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->signature;
    }

    /**
     * Accept a signature.
     *
     * @param Signature $signature
     * @return static
     */
    public function withSignature(Signature $signature): static
    {
        $clone = Copy::deep($this);
        $clone->signature = Copy::deep($signature);

        return $clone;
    }
}
