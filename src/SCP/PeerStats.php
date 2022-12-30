<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class PeerStats implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected NodeId $id;
    protected String100 $versionStr;
    protected UInt64 $messagesRead;
    protected UInt64 $messagesWritten;
    protected UInt64 $bytesRead;
    protected UInt64 $bytesWritten;
    protected UInt64 $secondsConnected;
    protected UInt64 $uniqueFloodBytesRecv;
    protected UInt64 $duplicateFloodBytesRecv;
    protected UInt64 $uniqueFetchBytesRecv;
    protected UInt64 $duplicateFetchBytesRecv;
    protected UInt64 $uniqueFloodMessageRecv;
    protected UInt64 $duplicateFloodMessageRecv;
    protected UInt64 $uniqueFetchMessageRecv;
    protected UInt64 $duplicateFetchMessageRecv;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->id)) {
            throw new InvalidArgumentException('Peer stats is missing a node Id');
        }

        if (!isset($this->versionStr)) {
            throw new InvalidArgumentException('Peer stats is missing a version string');
        }

        if (!isset($this->messagesRead)) {
            throw new InvalidArgumentException('Peer stats is missing a messages read count');
        }

        if (!isset($this->messagesWritten)) {
            throw new InvalidArgumentException('Peer stats is missing a messages written count');
        }

        if (!isset($this->bytesRead)) {
            throw new InvalidArgumentException('Peer stats is missing a bytes read count');
        }

        if (!isset($this->bytesWritten)) {
            throw new InvalidArgumentException('Peer stats is missing a bytes written count');
        }

        if (!isset($this->secondsConnected)) {
            throw new InvalidArgumentException('Peer stats is missing a seconds connected count');
        }

        if (!isset($this->uniqueFloodBytesRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a unique flood bytes received count');
        }

        if (!isset($this->duplicateFloodBytesRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a duplicate flood bytes received count');
        }

        if (!isset($this->uniqueFetchBytesRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a unique fetch bytes received count');
        }

        if (!isset($this->duplicateFetchBytesRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a duplicate fetch bytes received count');
        }

        if (!isset($this->uniqueFloodMessageRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a unique flood message received count');
        }

        if (!isset($this->duplicateFloodMessageRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a duplicate flood message received count');
        }

        if (!isset($this->uniqueFetchMessageRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a unique fetch message received count');
        }

        if (!isset($this->duplicateFetchMessageRecv)) {
            throw new InvalidArgumentException('Peer stats is missing a duplicate fetch message received count');
        }

        $xdr->write($this->id)
            ->write($this->versionStr)
            ->write($this->messagesRead)
            ->write($this->messagesWritten)
            ->write($this->bytesRead)
            ->write($this->bytesWritten)
            ->write($this->secondsConnected)
            ->write($this->uniqueFloodBytesRecv)
            ->write($this->duplicateFloodBytesRecv)
            ->write($this->uniqueFetchBytesRecv)
            ->write($this->duplicateFetchBytesRecv)
            ->write($this->uniqueFloodMessageRecv)
            ->write($this->duplicateFloodMessageRecv)
            ->write($this->uniqueFetchMessageRecv)
            ->write($this->duplicateFetchMessageRecv);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $peerStats = new static();
        $peerStats->id = $xdr->read(NodeId::class);
        $peerStats->versionStr = $xdr->read(String100::class);
        $peerStats->messagesRead = $xdr->read(UInt64::class);
        $peerStats->messagesWritten = $xdr->read(UInt64::class);
        $peerStats->bytesRead = $xdr->read(UInt64::class);
        $peerStats->bytesWritten = $xdr->read(UInt64::class);
        $peerStats->secondsConnected = $xdr->read(UInt64::class);
        $peerStats->uniqueFloodBytesRecv = $xdr->read(UInt64::class);
        $peerStats->duplicateFloodBytesRecv = $xdr->read(UInt64::class);
        $peerStats->uniqueFetchBytesRecv = $xdr->read(UInt64::class);
        $peerStats->duplicateFetchBytesRecv = $xdr->read(UInt64::class);
        $peerStats->uniqueFloodMessageRecv = $xdr->read(UInt64::class);
        $peerStats->duplicateFloodMessageRecv = $xdr->read(UInt64::class);
        $peerStats->uniqueFetchMessageRecv = $xdr->read(UInt64::class);
        $peerStats->duplicateFetchMessageRecv = $xdr->read(UInt64::class);

        return $peerStats;
    }

    /**
     * Get the Id.
     *
     * @return NodeId
     */
    public function getId(): NodeId
    {
        return $this->id;
    }

    /**
     * Accept an Id.
     *
     * @param NodeId $id
     * @return static
     */
    public function withId(NodeId $id): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->id = Copy::deep($id);

        return $clone;
    }

    /**
     * Get the version string.
     *
     * @return String100
     */
    public function getVersionStr(): String100
    {
        return $this->versionStr;
    }

    /**
     * Accept a version string.
     *
     * @param String100|string $versionStr
     * @return static
     */
    public function withVersionStr(String100|string $versionStr): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->versionStr = is_string($versionStr)
            ? String100::of($versionStr)
            : Copy::deep($versionStr);

        return $clone;
    }

    /**
     * Get the count of messages read.
     *
     * @return UInt64
     */
    public function getMessagesRead(): UInt64
    {
        return $this->messagesRead;
    }

    /**
     * Accept a count of messages read.
     *
     * @param UInt64 $messagesRead
     * @return static
     */
    public function withMessagesRead(UInt64 $messagesRead): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->messagesRead = Copy::deep($messagesRead);

        return $clone;
    }

    /**
     * Get the count of messages written.
     *
     * @return UInt64
     */
    public function getMessagesWritten(): UInt64
    {
        return $this->messagesWritten;
    }

    /**
     * Accept a count of messages written.
     *
     * @param UInt64 $messagesWritten
     * @return static
     */
    public function withMessagesWritten(UInt64 $messagesWritten): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->messagesWritten = Copy::deep($messagesWritten);

        return $clone;
    }

    /**
     * Get the count of bytes read.
     *
     * @return UInt64
     */
    public function getBytesRead(): UInt64
    {
        return $this->bytesRead;
    }

    /**
     * Accept a count of bytes read.
     *
     * @param UInt64 $bytesRead
     * @return static
     */
    public function withBytesRead(UInt64 $bytesRead): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->bytesRead = Copy::deep($bytesRead);

        return $clone;
    }

    /**
     * Get the count of bytes written.
     *
     * @return UInt64
     */
    public function getBytesWritten(): UInt64
    {
        return $this->bytesWritten;
    }

    /**
     * Accept a count of bytes written.
     *
     * @param UInt64 $bytesWritten
     * @return static
     */
    public function withBytesWritten(UInt64 $bytesWritten): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->bytesWritten = Copy::deep($bytesWritten);

        return $clone;
    }

    /**
     * Get the count of seconds connected.
     *
     * @return UInt64
     */
    public function getSecondsConnected(): UInt64
    {
        return $this->secondsConnected;
    }

    /**
     * Accept a count of seconds connected.
     *
     * @param UInt64 $secondsConnected
     * @return static
     */
    public function withSecondsConnected(UInt64 $secondsConnected): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->secondsConnected = Copy::deep($secondsConnected);

        return $clone;
    }

    /**
     * Get the count of unique flood bytes received.
     *
     * @return UInt64
     */
    public function getUniqueFloodBytesReceived(): UInt64
    {
        return $this->uniqueFloodBytesRecv;
    }

    /**
     * Accept a count of unique flood bytes received.
     *
     * @param UInt64 $uniqueFloodBytesRecv
     * @return static
     */
    public function withUniqueFloodBytesReceived(UInt64 $uniqueFloodBytesRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->uniqueFloodBytesRecv = Copy::deep($uniqueFloodBytesRecv);

        return $clone;
    }

    /**
     * Get the count of duplicate flood bytes received.
     *
     * @return UInt64
     */
    public function getDuplicateFloodBytesReceived(): UInt64
    {
        return $this->duplicateFloodBytesRecv;
    }

    /**
     * Accept a count of duplicate flood bytes received.
     *
     * @param UInt64 $duplicateFloodBytesRecv
     * @return static
     */
    public function withDuplicateFloodBytesReceived(UInt64 $duplicateFloodBytesRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->duplicateFloodBytesRecv = Copy::deep($duplicateFloodBytesRecv);

        return $clone;
    }

    /**
     * Get the count of unique fetch bytes received.
     *
     * @return UInt64
     */
    public function getUniqueFetchBytesReceived(): UInt64
    {
        return $this->uniqueFetchBytesRecv;
    }

    /**
     * Accept a count of unique fetch bytes received.
     *
     * @param UInt64 $uniqueFetchBytesRecv
     * @return static
     */
    public function withUniqueFetchBytesReceived(UInt64 $uniqueFetchBytesRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->uniqueFetchBytesRecv = Copy::deep($uniqueFetchBytesRecv);

        return $clone;
    }

    /**
     * Get the count of duplicate fetch bytes received.
     *
     * @return UInt64
     */
    public function getDuplicateFetchBytesReceived(): UInt64
    {
        return $this->duplicateFetchBytesRecv;
    }

    /**
     * Accept a count of duplicate fetch bytes received.
     *
     * @param UInt64 $duplicateFetchBytesRecv
     * @return static
     */
    public function withDuplicateFetchBytesReceived(UInt64 $duplicateFetchBytesRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->duplicateFetchBytesRecv = Copy::deep($duplicateFetchBytesRecv);

        return $clone;
    }

    /**
     * Get the count of unique flood messages received.
     *
     * @return UInt64
     */
    public function getUniqueFloodMessagesReceived(): UInt64
    {
        return $this->uniqueFloodMessageRecv;
    }

    /**
     * Accept a count of unique flood messages received.
     *
     * @param UInt64 $uniqueFloodMessageRecv
     * @return static
     */
    public function withUniqueFloodMessagesReceived(UInt64 $uniqueFloodMessageRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->uniqueFloodMessageRecv = Copy::deep($uniqueFloodMessageRecv);

        return $clone;
    }

    /**
     * Get the count of duplicate flood messages received.
     *
     * @return UInt64
     */
    public function getDuplicateFloodMessagesReceived(): UInt64
    {
        return $this->duplicateFloodMessageRecv;
    }

    /**
     * Accept a count of duplicate flood messages received.
     *
     * @param UInt64 $duplicateFloodMessageRecv
     * @return static
     */
    public function withDuplicateFloodMessagesReceived(UInt64 $duplicateFloodMessageRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->duplicateFloodMessageRecv = Copy::deep($duplicateFloodMessageRecv);

        return $clone;
    }

    /**
     * Get the count of unique fetch messages received.
     *
     * @return UInt64
     */
    public function getUniqueFetchMessagesReceived(): UInt64
    {
        return $this->uniqueFetchMessageRecv;
    }

    /**
     * Accept a count of unique fetch messages received.
     *
     * @param UInt64 $uniqueFetchMessageRecv
     * @return static
     */
    public function withUniqueFetchMessagesReceived(UInt64 $uniqueFetchMessageRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->uniqueFetchMessageRecv = Copy::deep($uniqueFetchMessageRecv);

        return $clone;
    }

    /**
     * Get the count of duplicate fetch messages received.
     *
     * @return UInt64
     */
    public function getDuplicateFetchMessagesReceived(): UInt64
    {
        return $this->duplicateFetchMessageRecv;
    }

    /**
     * Accept a count of duplicate fetch messages received.
     *
     * @param UInt64 $duplicateFetchMessageRecv
     * @return static
     */
    public function withDuplicateFetchMessagesReceived(UInt64 $duplicateFetchMessageRecv): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->duplicateFetchMessageRecv = Copy::deep($duplicateFetchMessageRecv);

        return $clone;
    }
}
