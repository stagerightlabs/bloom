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

final class TopologyResponseBody implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PeerStatsList $inboundPeers;
    protected PeerStatsList $outboundPeers;
    protected UInt32 $totalInboundPeers;
    protected UInt32 $totalOutboundPeers;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->inboundPeers)) {
            $this->inboundPeers = PeerStatsList::empty();
        }

        if (!isset($this->outboundPeers)) {
            $this->outboundPeers = PeerStatsList::empty();
        }

        if (!isset($this->totalInboundPeers)) {
            $this->totalInboundPeers = UInt32::of($this->inboundPeers->count());
        }

        if (!isset($this->totalOutboundPeers)) {
            $this->totalOutboundPeers = UInt32::of($this->outboundPeers->count());
        }

        $xdr->write($this->inboundPeers)
            ->write($this->outboundPeers)
            ->write($this->totalInboundPeers)
            ->write($this->totalOutboundPeers);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $topologyResponseBody = new static();
        $topologyResponseBody->inboundPeers = $xdr->read(PeerStatsList::class);
        $topologyResponseBody->outboundPeers = $xdr->read(PeerStatsList::class);
        $topologyResponseBody->totalInboundPeers = $xdr->read(UInt32::class);
        $topologyResponseBody->totalOutboundPeers = $xdr->read(UInt32::class);

        return $topologyResponseBody;
    }

    /**
     * Get the list of inbound peers.
     *
     * @return PeerStatsList
     */
    public function getInboundPeers(): PeerStatsList
    {
        return $this->inboundPeers;
    }

    /**
     * Accept a list of inbound peers.
     *
     * @param PeerStatsList $inboundPeers
     * @return static
     */
    public function withInboundPeers(PeerStatsList $inboundPeers): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->inboundPeers = Copy::deep($inboundPeers);

        return $clone;
    }

    /**
     * Get the list of outbound peers.
     *
     * @return PeerStatsList
     */
    public function getOutboundPeers(): PeerStatsList
    {
        return $this->outboundPeers;
    }

    /**
     * Accept a list of outbound peers.
     *
     * @param PeerStatsList $outboundPeers
     * @return static
     */
    public function withOutboundPeers(PeerStatsList $outboundPeers): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->outboundPeers = Copy::deep($outboundPeers);

        return $clone;
    }

    /**
     * Get the total inbound peer count.
     *
     * @return UInt32
     */
    public function getTotalInboundPeers(): UInt32
    {
        return $this->totalInboundPeers;
    }

    /**
     * Accept a total inbound peer count.
     *
     * @param UInt32|int $totalInboundPeers
     * @return static
     */
    public function withTotalInboundPeers(UInt32|int $totalInboundPeers): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->totalInboundPeers = is_int($totalInboundPeers)
            ? UInt32::of($totalInboundPeers)
            : Copy::deep($totalInboundPeers);

        return $clone;
    }

    /**
     * Get the total outbound peer count.
     *
     * @return UInt32
     */
    public function getTotalOutboundPeers(): UInt32
    {
        return $this->totalOutboundPeers;
    }

    /**
     * Accept a total outbound peer count.
     *
     * @param UInt32|int $totalOutboundPeers
     * @return static
     */
    public function withTotalOutboundPeers(UInt32|int $totalOutboundPeers): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->totalOutboundPeers = is_int($totalOutboundPeers)
            ? UInt32::of($totalOutboundPeers)
            : Copy::deep($totalOutboundPeers);

        return $clone;
    }
}
