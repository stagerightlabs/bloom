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

final class PeerAddress implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected PeerAddressIp $ip;
    protected UInt32 $port;
    protected UInt32 $numFailures;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ip)) {
            throw new InvalidArgumentException('The peer address has no IP');
        }

        if (!isset($this->port)) {
            throw new InvalidArgumentException('The peer address has no port');
        }

        if (!isset($this->numFailures)) {
            throw new InvalidArgumentException('The peer address is missing a failures count');
        }

        $xdr->write($this->ip)
            ->write($this->port)
            ->write($this->numFailures);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $peerAddress = new static();
        $peerAddress->ip = $xdr->read(PeerAddressIp::class);
        $peerAddress->port = $xdr->read(UInt32::class);
        $peerAddress->numFailures = $xdr->read(UInt32::class);

        return $peerAddress;
    }

    /**
     * Get the IP.
     *
     * @return PeerAddressIp
     */
    public function getIp(): PeerAddressIp
    {
        return $this->ip;
    }

    /**
     * Accept an IP.
     *
     * @param PeerAddressIp $ip
     * @return static
     */
    public function withIp(PeerAddressIp $ip): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ip = Copy::deep($ip);

        return $clone;
    }

    /**
     * Get the port.
     *
     * @return UInt32
     */
    public function getPort(): UInt32
    {
        return $this->port;
    }

    /**
     * Accept a port.
     *
     * @param UInt32|int $port
     * @return static
     */
    public function withPort(UInt32|int $port): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->port = is_int($port)
            ? UInt32::of($port)
            : Copy::deep($port);

        return $clone;
    }

    /**
     * Get the failure count.
     *
     * @return UInt32
     */
    public function getNumFailures(): UInt32
    {
        return $this->numFailures;
    }

    /**
     * Accept a failure count.
     *
     * @param UInt32|int $numFailures
     * @return static
     */
    public function withNumFailures(UInt32|int $numFailures): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->numFailures = is_int($numFailures)
            ? UInt32::of($numFailures)
            : Copy::deep($numFailures);

        return $clone;
    }
}
