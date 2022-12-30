<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class StellarValue implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Hash $txSetHash; // Transaction set to apply to previous ledger
    protected TimePoint $closeTime; // Network close time
    protected UpgradeTypeList $upgrades; // upgrades to apply to the previous ledger
    protected StellarValueExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->txSetHash)) {
            throw new InvalidArgumentException('The stellar value is missing a transaction set hash');
        }

        if (!isset($this->closeTime)) {
            throw new InvalidArgumentException('The stellar value is missing a close time');
        }

        if (!isset($this->upgrades)) {
            $this->upgrades = UpgradeTypeList::empty();
        }

        if (!isset($this->ext)) {
            $this->ext = StellarValueExt::basic();
        }

        $xdr->write($this->txSetHash)
            ->write($this->closeTime)
            ->write($this->upgrades)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $stellarValue = new static();
        $stellarValue->txSetHash = $xdr->read(Hash::class);
        $stellarValue->closeTime = $xdr->read(TimePoint::class);
        $stellarValue->upgrades = $xdr->read(UpgradeTypeList::class);
        $stellarValue->ext = $xdr->read(StellarValueExt::class);

        return $stellarValue;
    }

    /**
     * Get the txSetHash.
     *
     * @return Hash
     */
    public function getTxSetHash(): Hash
    {
        return $this->txSetHash;
    }

    /**
     * Accept a txSetHash.
     *
     * @param Hash $txSetHash
     * @return static
     */
    public function withTxSetHash(Hash $txSetHash): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txSetHash = Copy::deep($txSetHash);

        return $clone;
    }

    /**
     * Get the close time.
     *
     * @return TimePoint
     */
    public function getCloseTime(): TimePoint
    {
        return $this->closeTime;
    }

    /**
     * Accept a close time.
     *
     * @param TimePoint $closeTime
     * @return static
     */
    public function withCloseTime(TimePoint $closeTime): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->closeTime = Copy::deep($closeTime);

        return $clone;
    }

    /**
     * Get the upgrades.
     *
     * @return UpgradeTypeList
     */
    public function getUpgrades(): UpgradeTypeList
    {
        return $this->upgrades;
    }

    /**
     * Accept a list of upgrades.
     *
     * @param UpgradeTypeList $upgrades
     * @return static
     */
    public function withUpgrades(UpgradeTypeList $upgrades): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->upgrades = Copy::deep($upgrades);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return StellarValueExt
     */
    public function getExtension(): StellarValueExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param StellarValueExt $ext
     * @return static
     */
    public function withExtension(StellarValueExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
