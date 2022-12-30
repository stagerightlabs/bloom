<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpStatementConfirm implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ScpBallot $ballot;
    protected UInt32 $nPrepared;
    protected UInt32 $nCommit;
    protected UInt32 $nH;
    protected Hash $quorumSetHash;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ballot)) {
            throw new InvalidArgumentException('The SCP statement confirm is missing a ballot');
        }

        if (!isset($this->nPrepared)) {
            throw new InvalidArgumentException('The SCP statement confirm is missing a p.n value');
        }

        if (!isset($this->nCommit)) {
            throw new InvalidArgumentException('The SCP statement confirm is missing a c.n value');
        }

        if (!isset($this->nH)) {
            throw new InvalidArgumentException('The SCP statement confirm is missing a h.n value');
        }

        if (!isset($this->quorumSetHash)) {
            throw new InvalidArgumentException('The SCP statement confirm is missing a quorum set hash');
        }

        $xdr->write($this->ballot)
            ->write($this->nPrepared)
            ->write($this->nCommit)
            ->write($this->nH)
            ->write($this->quorumSetHash);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpStatementConfirm = new static();
        $scpStatementConfirm->ballot = $xdr->read(ScpBallot::class);
        $scpStatementConfirm->nPrepared = $xdr->read(UInt32::class);
        $scpStatementConfirm->nCommit = $xdr->read(UInt32::class);
        $scpStatementConfirm->nH = $xdr->read(UInt32::class);
        $scpStatementConfirm->quorumSetHash = $xdr->read(Hash::class);

        return $scpStatementConfirm;
    }

    /**
     * Get the ballot.
     *
     * @return ScpBallot
     */
    public function getBallot(): ScpBallot
    {
        return $this->ballot;
    }

    /**
     * Accept a ballot.
     *
     * @param ScpBallot $ballot
     * @return static
     */
    public function withBallot(ScpBallot $ballot): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ballot = Copy::deep($ballot);

        return $clone;
    }

    /**
     * Get the N prepared.
     *
     * @return UInt32
     */
    public function getNPrepared(): UInt32
    {
        return $this->nPrepared;
    }

    /**
     * Accept an N prepared.
     *
     * @param UInt32|int $nPrepared
     * @return static
     */
    public function withNPrepared(UInt32|int $nPrepared): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nPrepared = is_int($nPrepared)
            ? UInt32::of($nPrepared)
            : Copy::deep($nPrepared);

        return $clone;
    }

    /**
     * Get the N commit.
     *
     * @return UInt32
     */
    public function getNCommit(): UInt32
    {
        return $this->nCommit;
    }

    /**
     * Accept an N commit.
     *
     * @param UInt32|int $nCommit
     * @return static
     */
    public function withNCommit(UInt32|int $nCommit): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nCommit = is_int($nCommit)
            ? UInt32::of($nCommit)
            : Copy::deep($nCommit);

        return $clone;
    }

    /**
     * Get the NH.
     *
     * @return UInt32
     */
    public function getNH(): UInt32
    {
        return $this->nH;
    }

    /**
     * Accept an NH.
     *
     * @param UInt32|int $nH
     * @return static
     */
    public function withNH(UInt32|int $nH): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nH = is_int($nH)
            ? UInt32::of($nH)
            : Copy::deep($nH);

        return $clone;
    }

    /**
     * Get the quorum set hash.
     *
     * @return Hash
     */
    public function getQuorumSetHash(): Hash
    {
        return $this->quorumSetHash;
    }

    /**
     * Accept a quorum set hash.
     *
     * @param Hash $quorumSetHash
     * @return static
     */
    public function withQuorumSetHash(Hash $quorumSetHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->quorumSetHash = Copy::deep($quorumSetHash);

        return $clone;
    }
}
