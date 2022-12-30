<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String100;
use StageRightLabs\Bloom\Primitives\UInt256;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Hello implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $ledgerVersion;
    protected UInt32 $overlayVersion;
    protected UInt32 $overlayMinVersion;
    protected Hash $networkId;
    protected String100 $versionStr;
    protected int $listeningPort;
    protected NodeId $peerId;
    protected AuthCert $cert;
    protected UInt256 $nonce;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ledgerVersion)) {
            throw new InvalidArgumentException('The hello struct is missing a ledger version');
        }

        if (!isset($this->overlayVersion)) {
            throw new InvalidArgumentException('The hello struct is missing an overlay version');
        }

        if (!isset($this->overlayMinVersion)) {
            throw new InvalidArgumentException('The hello struct is missing an overlay min version');
        }

        if (!isset($this->networkId)) {
            throw new InvalidArgumentException('The hello struct is missing a network ID');
        }

        if (!isset($this->versionStr)) {
            throw new InvalidArgumentException('The hello struct is missing a version string');
        }

        if (!isset($this->listeningPort)) {
            throw new InvalidArgumentException('The hello struct is missing a listening port');
        }

        if (!isset($this->peerId)) {
            throw new InvalidArgumentException('The hello struct is missing a peer ID');
        }

        if (!isset($this->cert)) {
            throw new InvalidArgumentException('The hello struct is missing an auth cert');
        }

        if (!isset($this->nonce)) {
            throw new InvalidArgumentException('The hello struct is missing a nonce');
        }

        $xdr->write($this->ledgerVersion)
            ->write($this->overlayVersion)
            ->write($this->overlayMinVersion)
            ->write($this->networkId)
            ->write($this->versionStr)
            ->write($this->listeningPort, XDR::INT)
            ->write($this->peerId)
            ->write($this->cert)
            ->write($this->nonce);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $hello = new static();
        $hello->ledgerVersion = $xdr->read(UInt32::class);
        $hello->overlayVersion = $xdr->read(UInt32::class);
        $hello->overlayMinVersion = $xdr->read(UInt32::class);
        $hello->networkId = $xdr->read(Hash::class);
        $hello->versionStr = $xdr->read(String100::class);
        $hello->listeningPort = $xdr->read(XDR::INT);
        $hello->peerId = $xdr->read(NodeId::class);
        $hello->cert = $xdr->read(AuthCert::class);
        $hello->nonce = $xdr->read(UInt256::class);

        return $hello;
    }

    /**
     * Get the ledger version.
     *
     * @return UInt32
     */
    public function getLedgerVersion(): UInt32
    {
        return $this->ledgerVersion;
    }

    /**
     * Accept a ledger version.
     *
     * @param UInt32|int $ledgerVersion
     * @return static
     */
    public function withLedgerVersion(UInt32|int $ledgerVersion): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ledgerVersion = is_int($ledgerVersion)
            ? UInt32::of($ledgerVersion)
            : Copy::deep($ledgerVersion);

        return $clone;
    }

    /**
     * Get the overlay version.
     *
     * @return UInt32
     */
    public function getOverlayVersion(): UInt32
    {
        return $this->overlayVersion;
    }

    /**
     * Accept an overlay version.
     *
     * @param UInt32|int $overlayVersion
     * @return static
     */
    public function withOverlayVersion(UInt32|int $overlayVersion): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->overlayVersion = is_int($overlayVersion)
            ? UInt32::of($overlayVersion)
            : Copy::deep($overlayVersion);

        return $clone;
    }

    /**
     * Get the overlay min version.
     *
     * @return UInt32
     */
    public function getOverlayMinVersion(): UInt32
    {
        return $this->overlayMinVersion;
    }

    /**
     * Accept an overlay min version.
     *
     * @param UInt32|int $overlayMinVersion
     * @return static
     */
    public function withOverlayMinVersion(UInt32|int $overlayMinVersion): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->overlayMinVersion = is_int($overlayMinVersion)
            ? UInt32::of($overlayMinVersion)
            : Copy::deep($overlayMinVersion);

        return $clone;
    }

    /**
     * Get the network ID.
     *
     * @return Hash
     */
    public function getNetworkId(): Hash
    {
        return $this->networkId;
    }

    /**
     * Accept a network Id.
     *
     * @param Hash $networkId
     * @return static
     */
    public function withNetworkId(Hash $networkId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->networkId = Copy::deep($networkId);

        return $clone;
    }

    /**
     * Get the version string.
     *
     * @return String100
     */
    public function getVersionString(): String100
    {
        return $this->versionStr;
    }

    /**
     * Accept a version string.
     *
     * @param String100|string $versionStr
     * @return static
     */
    public function withVersionString(String100|string $versionStr): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->versionStr = is_string($versionStr)
            ? String100::of($versionStr)
            : Copy::deep($versionStr);

        return $clone;
    }

    /**
     * Get the listening port.
     *
     * @return int
     */
    public function getListeningPort(): int
    {
        return $this->listeningPort;
    }

    /**
     * Accept a listening port.
     *
     * @param int $listeningPort
     * @return static
     */
    public function withListeningPort(int $listeningPort): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->listeningPort = $listeningPort;

        return $clone;
    }

    /**
     * Get the peer ID.
     *
     * @return NodeId
     */
    public function getPeerId(): NodeId
    {
        return $this->peerId;
    }

    /**
     * Accept a peer ID.
     *
     * @param NodeId $peerId
     * @return static
     */
    public function withPeerId(NodeId $peerId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->peerId = Copy::deep($peerId);

        return $clone;
    }

    /**
     * Get the auth cert.
     *
     * @return AuthCert
     */
    public function getCert(): AuthCert
    {
        return $this->cert;
    }

    /**
     * Accept an auth cert.
     *
     * @param AuthCert $cert
     * @return static
     */
    public function withCert(AuthCert $cert): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->cert = Copy::deep($cert);

        return $clone;
    }

    /**
     * Get the nonce.
     *
     * @return UInt256
     */
    public function getNonce(): UInt256
    {
        return $this->nonce;
    }

    /**
     * Accept a nonce.
     *
     * @param UInt256 $nonce
     * @return static
     */
    public function withNonce(UInt256 $nonce): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nonce = Copy::deep($nonce);

        return $clone;
    }
}
