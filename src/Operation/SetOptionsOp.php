<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\OptionalAccountId;
use StageRightLabs\Bloom\Account\OptionalSigner;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\OptionalString32;
use StageRightLabs\Bloom\Primitives\OptionalUInt32;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SetOptionsOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected OptionalAccountId $inflationDest; // sets the inflation destination
    protected OptionalUInt32 $clearFlags; // which flags to clear
    protected OptionalUInt32 $setFlags; // which flags to set
    protected OptionalUInt32 $masterWeight; // Weight of the master account
    protected OptionalUInt32 $lowThreshold;
    protected OptionalUInt32 $medThreshold;
    protected OptionalUInt32 $highThreshold;
    protected OptionalString32 $homeDomain; // Sets the home domain
    protected OptionalSigner $signer; // Add, update or remove a signer for the account. Signer is deleted if the weight is 0.

    /**
     * Create a new set-options operation.
     *
     * @param AccountId|null $inflationDestination
     * @param UInt32|integer|null $clearFlags
     * @param UInt32|integer|null $setFlags
     * @param UInt32|integer|null $masterWeight
     * @param UInt32|integer|null $lowThreshold
     * @param UInt32|integer|null $mediumThreshold
     * @param UInt32|integer|null $highThreshold
     * @param String32|string|null $homeDomain
     * @param Signer|null $signer
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        AccountId $inflationDestination = null,
        UInt32|int $clearFlags = null,
        UInt32|int $setFlags = null,
        UInt32|int $masterWeight = null,
        UInt32|int $lowThreshold = null,
        UInt32|int $mediumThreshold = null,
        UInt32|int $highThreshold = null,
        String32|string $homeDomain = null,
        Signer $signer = null,
        Addressable|string $source = null,
    ): Operation {
        $setOptionsOp = (new static())
            ->withInflationDestination($inflationDestination)
            ->withClearFlags($clearFlags)
            ->withSetFlags($setFlags)
            ->withMasterWeight($masterWeight)
            ->withLowThreshold($lowThreshold)
            ->withMediumThreshold($mediumThreshold)
            ->withHighThreshold($highThreshold)
            ->withHomeDomain($homeDomain)
            ->withSigner($signer);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::SET_OPTIONS, $setOptionsOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return true;
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        if (
            (isset($this->masterWeight) && !is_null($this->masterWeight))
            || (isset($this->lowThreshold) && !is_null($this->lowThreshold))
            || (isset($this->medThreshold) && !is_null($this->medThreshold))
            || (isset($this->highThreshold) && !is_null($this->highThreshold))
            || (isset($this->signer) && !is_null($this->signer))
        ) {
            return Thresholds::CATEGORY_HIGH;
        }

        return Thresholds::CATEGORY_MEDIUM;
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->inflationDest)) {
            $this->inflationDest = OptionalAccountId::none();
        }

        if (!isset($this->clearFlags)) {
            $this->clearFlags = OptionalUInt32::none();
        }

        if (!isset($this->setFlags)) {
            $this->setFlags = OptionalUInt32::none();
        }

        if (!isset($this->masterWeight)) {
            $this->masterWeight = OptionalUInt32::none();
        }

        if (!isset($this->lowThreshold)) {
            $this->lowThreshold = OptionalUInt32::none();
        }

        if (!isset($this->medThreshold)) {
            $this->medThreshold = OptionalUInt32::none();
        }

        if (!isset($this->highThreshold)) {
            $this->highThreshold = OptionalUInt32::none();
        }

        if (!isset($this->homeDomain)) {
            $this->homeDomain = OptionalString32::none();
        }

        if (!isset($this->signer)) {
            $this->signer = OptionalSigner::none();
        }

        $xdr->write($this->inflationDest)
            ->write($this->clearFlags)
            ->write($this->setFlags)
            ->write($this->masterWeight)
            ->write($this->lowThreshold)
            ->write($this->medThreshold)
            ->write($this->highThreshold)
            ->write($this->homeDomain)
            ->write($this->signer);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $setOptionsOp = new static();
        $setOptionsOp->inflationDest = $xdr->read(OptionalAccountId::class);
        $setOptionsOp->clearFlags = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->setFlags = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->masterWeight = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->lowThreshold = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->medThreshold = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->highThreshold = $xdr->read(OptionalUInt32::class);
        $setOptionsOp->homeDomain = $xdr->read(OptionalString32::class);
        $setOptionsOp->signer = $xdr->read(OptionalSigner::class);

        return $setOptionsOp;
    }

    /**
     * Get the inflation destination, if present.
     *
     * @return AccountId|null
     */
    public function getInflationDestination(): ?AccountId
    {
        return $this->inflationDest->hasValue()
            ? $this->inflationDest->unwrap()
            : null;
    }

    /**
     * Accept an inflation destination.
     *
     * @param AccountId|OptionalAccountId|null $inflationDest
     * @return static
     */
    public function withInflationDestination(AccountId|OptionalAccountId|null $inflationDest): static
    {
        if (is_null($inflationDest)) {
            $inflationDest = OptionalAccountId::none();
        } elseif ($inflationDest instanceof AccountId) {
            $inflationDest = OptionalAccountId::some($inflationDest);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->inflationDest = Copy::deep($inflationDest);

        return $clone;
    }

    /**
     * Get the flags to be cleared, if present.
     *
     * @return UInt32|null
     */
    public function getClearFlags(): ?UInt32
    {
        return $this->clearFlags->unwrap();
    }

    /**
     * Accept flags to be cleared.
     *
     * @param UInt32|OptionalUInt32|int|null $clearFlags
     * @return static
     */
    public function withClearFlags(UInt32|OptionalUint32|int|null $clearFlags): static
    {
        if (is_null($clearFlags)) {
            $clearFlags = OptionalUInt32::none();
        }

        if (is_int($clearFlags)) {
            $clearFlags = OptionalUInt32::some($clearFlags);
        }

        if ($clearFlags instanceof UInt32) {
            $clearFlags = OptionalUInt32::some($clearFlags);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->clearFlags = Copy::deep($clearFlags);

        return $clone;
    }

    /**
     * Get the flags to be cleared, if present.
     *
     * @return UInt32|null
     */
    public function getSetFlags(): ?UInt32
    {
        return $this->setFlags->unwrap();
    }

    /**
     * Accept flags to be set.
     *
     * @param Uint32|OptionalUInt32|int|null $setFlags
     * @return static
     */
    public function withSetFlags(Uint32|OptionalUInt32|int|null $setFlags): static
    {
        if (is_null($setFlags)) {
            $setFlags = OptionalUInt32::none();
        }

        if (is_int($setFlags)) {
            $setFlags = OptionalUInt32::some($setFlags);
        }

        if ($setFlags instanceof UInt32) {
            $setFlags = OptionalUInt32::some($setFlags);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->setFlags = Copy::deep($setFlags);

        return $clone;
    }

    /**
     * Get the master weight, if present.
     *
     * @return UInt32|null
     */
    public function getMasterWeight(): ?UInt32
    {
        return $this->masterWeight->unwrap();
    }

    /**
     * Accept a master weight.
     *
     * @param UInt32|OptionalUInt32|int|null $masterWeight
     * @return static
     */
    public function withMasterWeight(UInt32|OptionalUInt32|int|null $masterWeight): static
    {
        if (is_null($masterWeight)) {
            $masterWeight = OptionalUInt32::none();
        }

        if (is_int($masterWeight)) {
            $masterWeight = OptionalUInt32::some($masterWeight);
        }

        if ($masterWeight instanceof UInt32) {
            $masterWeight = OptionalUInt32::some($masterWeight);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->masterWeight = Copy::deep($masterWeight);

        return $clone;
    }

    /**
     * Get the low threshold, if present.
     *
     * @return UInt32|null
     */
    public function getLowThreshold(): ?UInt32
    {
        return $this->lowThreshold->unwrap();
    }

    /**
     * Accept a low threshold.
     *
     * @param UInt32|OptionalUInt32|int|null $lowThreshold
     * @return static
     */
    public function withLowThreshold(UInt32|OptionalUInt32|int|null $lowThreshold): static
    {
        if (is_null($lowThreshold)) {
            $lowThreshold = OptionalUInt32::none();
        }

        if (is_int($lowThreshold)) {
            $lowThreshold = OptionalUInt32::some($lowThreshold);
        }

        if ($lowThreshold instanceof UInt32) {
            $lowThreshold = OptionalUInt32::some($lowThreshold);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->lowThreshold = Copy::deep($lowThreshold);

        return $clone;
    }

    /**
     * Get the medium threshold, if present.
     *
     * @return UInt32|null
     */
    public function getMediumThreshold(): ?UInt32
    {
        return $this->medThreshold->unwrap();
    }

    /**
     * Accept a medium threshold.
     *
     * @param UInt32|OptionalUInt32|int|null $medThreshold
     * @return static
     */
    public function withMediumThreshold(UInt32|OptionalUInt32|int|null $medThreshold): static
    {
        if (is_null($medThreshold)) {
            $medThreshold = OptionalUInt32::none();
        }

        if (is_int($medThreshold)) {
            $medThreshold = OptionalUInt32::some($medThreshold);
        }

        if ($medThreshold instanceof UInt32) {
            $medThreshold = OptionalUInt32::some($medThreshold);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->medThreshold = Copy::deep($medThreshold);

        return $clone;
    }

    /**
     * Get the high threshold, if present.
     *
     * @return UInt32|null
     */
    public function getHighThreshold(): ?UInt32
    {
        return $this->highThreshold->unwrap();
    }

    /**
     * Accept a high threshold.
     *
     * @param UInt32|OptionalUInt32|int|null $highThreshold
     * @return static
     */
    public function withHighThreshold(UInt32|OptionalUInt32|int|null $highThreshold): static
    {
        if (is_null($highThreshold)) {
            $highThreshold = OptionalUInt32::none();
        }

        if (is_int($highThreshold)) {
            $highThreshold = OptionalUInt32::some($highThreshold);
        }

        if ($highThreshold instanceof UInt32) {
            $highThreshold = OptionalUInt32::some($highThreshold);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->highThreshold = Copy::deep($highThreshold);

        return $clone;
    }

    /**
     * Get the home domain, if present.
     *
     * @return String32|null
     */
    public function getHomeDomain(): ?String32
    {
        return $this->homeDomain->unwrap();
    }

    /**
     * Accept a home domain.
     *
     * @param String32|OptionalString32|string|null $homeDomain
     * @return static
     */
    public function withHomeDomain(String32|OptionalString32|string|null $homeDomain): static
    {
        if (is_null($homeDomain)) {
            $homeDomain = OptionalString32::none();
        }

        if (is_string($homeDomain)) {
            $homeDomain = OptionalString32::some($homeDomain);
        }

        if ($homeDomain instanceof String32) {
            $homeDomain = OptionalString32::some($homeDomain);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->homeDomain = Copy::deep($homeDomain);

        return $clone;
    }

    /**
     * Get the signer, if present.
     *
     * @return Signer|null
     */
    public function getSigner(): ?Signer
    {
        return $this->signer->unwrap();
    }

    /**
     * Accept a signer.
     *
     * @param Signer|OptionalSigner|null $signer
     * @return static
     */
    public function withSigner(Signer|OptionalSigner|null $signer): static
    {
        if (is_null($signer)) {
            $signer = OptionalSigner::none();
        }

        if ($signer instanceof Signer) {
            $signer = OptionalSigner::some($signer);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->signer = Copy::deep($signer);

        return $clone;
    }
}
