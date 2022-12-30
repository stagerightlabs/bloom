<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\ValueList;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpNomination implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $quorumSetHash;
    protected ValueList $votes;
    protected ValueList $accepted;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->quorumSetHash)) {
            throw new InvalidArgumentException('The SCP nomination is missing a quorum set hash');
        }

        if (!isset($this->votes)) {
            $this->votes = ValueList::empty();
        }

        if (!isset($this->accepted)) {
            $this->accepted = ValueList::empty();
        }

        $xdr->write($this->quorumSetHash)
            ->write($this->votes)
            ->write($this->accepted);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpNomination = new static();
        $scpNomination->quorumSetHash = $xdr->read(Hash::class);
        $scpNomination->votes = $xdr->read(ValueList::class);
        $scpNomination->accepted = $xdr->read(ValueList::class);

        return $scpNomination;
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
     * Get the votes.
     *
     * @return ValueList
     */
    public function getVotes(): ValueList
    {
        return $this->votes;
    }

    /**
     * Accept a list of votes.
     *
     * @param ValueList $votes
     * @return static
     */
    public function withVotes(ValueList $votes): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->votes = Copy::deep($votes);

        return $clone;
    }

    /**
     * Get the accepted votes.
     *
     * @return ValueList
     */
    public function getAccepted(): ValueList
    {
        return $this->accepted;
    }

    /**
     * Accept a list of accepted votes.
     *
     * @param ValueList $accepted
     * @return static
     */
    public function withAccepted(ValueList $accepted): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->accepted = Copy::deep($accepted);

        return $clone;
    }
}
