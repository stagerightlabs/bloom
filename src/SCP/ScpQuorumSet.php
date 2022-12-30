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

final class ScpQuorumSet implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $threshold;
    protected NodeIdList $validators;
    protected ScpQuorumSetList $innerSets;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->threshold)) {
            throw new InvalidArgumentException('The SCP quorum set is missing a threshold');
        }

        if (!isset($this->validators)) {
            throw new InvalidArgumentException('The SCP quorum set is missing a list of validators');
        }

        if (!isset($this->innerSets)) {
            $this->innerSets = ScpQuorumSetList::empty();
        }

        $xdr->write($this->threshold)
            ->write($this->validators)
            ->write($this->innerSets);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpQuorumSet = new static();
        $scpQuorumSet->threshold = $xdr->read(UInt32::class);
        $scpQuorumSet->validators = $xdr->read(NodeIdList::class);
        $scpQuorumSet->innerSets = $xdr->read(ScpQuorumSetList::class);

        return $scpQuorumSet;
    }

    /**
     * Get the threshold.
     *
     * @return UInt32
     */
    public function getThreshold(): UInt32
    {
        return $this->threshold;
    }

    /**
     * Accept a threshold.
     *
     * @param UInt32|int $threshold
     * @return static
     */
    public function withThreshold(UInt32|int $threshold): static
    {
        if (is_int($threshold)) {
            $threshold = UInt32::of($threshold);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->threshold = Copy::deep($threshold);

        return $clone;
    }

    /**
     * Get the list of validators.
     *
     * @return NodeIdList
     */
    public function getValidators(): NodeIdList
    {
        return $this->validators;
    }

    /**
     * Accept a list of validators.
     *
     * @param NodeIdList $validators
     * @return static
     */
    public function withValidators(NodeIdList $validators): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->validators = Copy::deep($validators);

        return $clone;
    }

    /**
     * Get the list of inner sets.
     *
     * @return ScpQuorumSetList
     */
    public function getInnerSets(): ScpQuorumSetList
    {
        return $this->innerSets;
    }

    /**
     * Accept a list of inner sets.
     *
     * @param ScpQuorumSetList $innerSets
     * @return static
     */
    public function withInnerSets(ScpQuorumSetList $innerSets): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->innerSets = Copy::deep($innerSets);

        return $clone;
    }
}
