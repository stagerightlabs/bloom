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

final class ScpStatementExternalize implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ScpBallot $commit;
    protected UInt32 $nH;
    protected Hash $commitQuorumSetHash;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->commit)) {
            throw new InvalidArgumentException('The SCP statement externalize is missing a commit ballot');
        }

        if (!isset($this->nH)) {
            throw new InvalidArgumentException('The SCP statement externalize is missing a nH value');
        }

        if (!isset($this->commitQuorumSetHash)) {
            throw new InvalidArgumentException('The SCP statement externalize is missing a commit quorum set hash');
        }

        $xdr->write($this->commit)
            ->write($this->nH)
            ->write($this->commitQuorumSetHash);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpStatementExternalize = new static();
        $scpStatementExternalize->commit = $xdr->read(ScpBallot::class);
        $scpStatementExternalize->nH = $xdr->read(UInt32::class);
        $scpStatementExternalize->commitQuorumSetHash = $xdr->read(Hash::class);

        return $scpStatementExternalize;
    }

    /**
     * Get the commit ballot.
     *
     * @return ScpBallot
     */
    public function getCommit(): ScpBallot
    {
        return $this->commit;
    }

    /**
     * Accept a commit ballot.
     *
     * @param ScpBallot $commit
     * @return static
     */
    public function withCommit(ScpBallot $commit): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->commit = Copy::deep($commit);

        return $clone;
    }

    /**
     * Get the nH value.
     *
     * @return UInt32
     */
    public function getNH(): UInt32
    {
        return $this->nH;
    }

    /**
     * Accept a nH value.
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
     * Get the commit quorum set hash.
     *
     * @return Hash
     */
    public function getCommitQuorumSetHash(): Hash
    {
        return $this->commitQuorumSetHash;
    }

    /**
     * Accept a commit quorum set hash.
     *
     * @param Hash $commitQuorumSetHash
     * @return static
     */
    public function withCommitQuorumSetHash(Hash $commitQuorumSetHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->commitQuorumSetHash = Copy::deep($commitQuorumSetHash);

        return $clone;
    }
}
