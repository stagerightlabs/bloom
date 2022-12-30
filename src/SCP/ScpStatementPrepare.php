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

final class ScpStatementPrepare implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $quorumSetHash;
    protected ScpBallot $ballot;
    protected OptionalScpBallot $prepared;
    protected OptionalScpBallot $preparedPrime;
    protected UInt32 $nC;
    protected UInt32 $nH;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->quorumSetHash)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing a quorum set hash');
        }

        if (!isset($this->ballot)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing a ballot');
        }

        if (!isset($this->prepared)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing a prepared ballot');
        }

        if (!isset($this->preparedPrime)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing a prime prepared ballot');
        }

        if (!isset($this->nC)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing an nC value');
        }

        if (!isset($this->nH)) {
            throw new InvalidArgumentException('The SCP statement prepare is missing an nH value');
        }

        $xdr->write($this->quorumSetHash)
            ->write($this->ballot)
            ->write($this->prepared)
            ->write($this->preparedPrime)
            ->write($this->nC)
            ->write($this->nH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpStatementPrepare = new static();
        $scpStatementPrepare->quorumSetHash = $xdr->read(Hash::class);
        $scpStatementPrepare->ballot = $xdr->read(ScpBallot::class);
        $scpStatementPrepare->prepared = $xdr->read(OptionalScpBallot::class);
        $scpStatementPrepare->preparedPrime = $xdr->read(OptionalScpBallot::class);
        $scpStatementPrepare->nC = $xdr->read(UInt32::class);
        $scpStatementPrepare->nH = $xdr->read(UInt32::class);

        return $scpStatementPrepare;
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
     * Get the prepared ballot, if present.
     *
     * @return ScpBallot|null
     */
    public function getPrepared(): ?ScpBallot
    {
        if (isset($this->prepared) && $this->prepared->hasValue()) {
            return $this->prepared->unwrap();
        }

        return null;
    }

    /**
     * Accept a prepared ballot.
     *
     * @param OptionalScpBallot|ScpBallot $prepared
     * @return static
     */
    public function withPrepared(OptionalScpBallot|ScpBallot $prepared): static
    {
        if ($prepared instanceof ScpBallot) {
            $prepared = OptionalScpBallot::some($prepared);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->prepared = Copy::deep($prepared);

        return $clone;
    }

    /**
     * Get the prime prepared ballot, if present.
     *
     * @return ScpBallot|null
     */
    public function getPreparedPrime(): ?ScpBallot
    {
        if (isset($this->preparedPrime) && $this->preparedPrime->hasValue()) {
            return $this->preparedPrime->unwrap();
        }

        return null;
    }

    /**
     * Accept a prime prepared ballot.
     *
     * @param OptionalScpBallot|ScpBallot $preparedPrime
     * @return static
     */
    public function withPreparedPrime(OptionalScpBallot|ScpBallot $preparedPrime): static
    {
        if ($preparedPrime instanceof ScpBallot) {
            $preparedPrime = OptionalScpBallot::some($preparedPrime);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->preparedPrime = Copy::deep($preparedPrime);

        return $clone;
    }

    /**
     * Get the nC value.
     *
     * @return UInt32
     */
    public function getNC(): UInt32
    {
        return $this->nC;
    }

    /**
     * Accept an nC value.
     *
     * @param UInt32|int $nC
     * @return static
     */
    public function withNC(UInt32|int $nC): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->nC = is_int($nC)
            ? UInt32::of($nC)
            : Copy::deep($nC);

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
     * Accept an nH value.
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
}
