<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use DateTime;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\ExtensionPoint;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\TimePoint;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class AccountEntryExtensionV3 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ExtensionPoint $ext;
    protected UInt32 $seqLedger;
    protected TimePoint $seqTime;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->ext)) {
            $this->ext = ExtensionPoint::empty();
        }

        if (!isset($this->seqLedger)) {
            throw new InvalidArgumentException('The account entry extension v3 is missing a seqLedger');
        }

        if (!isset($this->seqTime)) {
            throw new InvalidArgumentException('The account entry extension v3 is missing a seqTime');
        }

        $xdr->write($this->ext)->write($this->seqLedger)->write($this->seqTime);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $accountEntryExtensionV3 = new static();
        $accountEntryExtensionV3->ext = $xdr->read(ExtensionPoint::class);
        $accountEntryExtensionV3->seqLedger = $xdr->read(UInt32::class);
        $accountEntryExtensionV3->seqTime = $xdr->read(TimePoint::class);

        return $accountEntryExtensionV3;
    }

    /**
     * Get the extension point.
     *
     * @return ExtensionPoint
     */
    public function getExtensionPoint(): ExtensionPoint
    {
        return $this->ext;
    }

    /**
     * Accept an extension point.
     *
     * @param ExtensionPoint $ext
     * @return static
     */
    public function withExtensionPoint(ExtensionPoint $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }

    /**
     * Get the sequence ledger.
     *
     * @return UInt32
     */
    public function getSeqLedger(): UInt32
    {
        return $this->seqLedger;
    }

    /**
     * Accept a sequence ledger.
     *
     * @param UInt32 $seqLedger
     * @return static
     */
    public function withSeqLedger(UInt32 $seqLedger): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->seqLedger = Copy::deep($seqLedger);

        return $clone;
    }

    /**
     * Get the sequence time.
     *
     * @return TimePoint
     */
    public function getSeqTime(): TimePoint
    {
        return $this->seqTime;
    }

    /**
     * Accept a sequence time.
     *
     * @param TimePoint|DateTime|string|int $seqTime
     * @return static
     */
    public function withSeqTime(TimePoint|DateTime|string|int $seqTime): static
    {
        // Resolve the input to a time point
        if (is_string($seqTime)) {
            $seqTime = TimePoint::fromNativeString($seqTime);
        } elseif (is_int($seqTime)) {
            $seqTime = TimePoint::fromUnixEpoch($seqTime);
        } elseif ($seqTime instanceof DateTime) {
            $seqTime = TimePoint::fromDateTime($seqTime);
        } else {
            $seqTime = Copy::deep($seqTime);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->seqTime = $seqTime;

        return $clone;
    }
}
