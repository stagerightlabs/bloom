<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class LedgerUpgrade extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LedgerUpgradeType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LedgerUpgradeType::LEDGER_UPGRADE_VERSION         => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_BASE_FEE        => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_MAX_TX_SET_SIZE => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_BASE_RESERVE    => UInt32::class,
            LedgerUpgradeType::LEDGER_UPGRADE_FLAGS           => UInt32::class,
        ];
    }


    /**
     * Create a new instance by wrapping a version number.
     *
     * @param UInt32|int $version
     * @return static
     */
    public static function wrapVersion(UInt32|int $version): static
    {
        if (is_int($version)) {
            $version = UInt32::of($version);
        }

        $ledgerUpgrade = new static();
        $ledgerUpgrade->discriminator = LedgerUpgradeType::version();
        $ledgerUpgrade->value = $version;

        return $ledgerUpgrade;
    }

    /**
     * Create a new instance by wrapping a base fee.
     *
     * @param UInt32|int $baseFee
     * @return static
     */
    public static function wrapBaseFee(UInt32|int $baseFee): static
    {
        if (is_int($baseFee)) {
            $baseFee = UInt32::of($baseFee);
        }

        $ledgerUpgrade = new static();
        $ledgerUpgrade->discriminator = LedgerUpgradeType::baseFee();
        $ledgerUpgrade->value = $baseFee;

        return $ledgerUpgrade;
    }

    /**
     * Create a new instance by wrapping a max tx set size.
     *
     * @param UInt32|int $maxTxSetSize
     * @return static
     */
    public static function wrapMaxTxSetSize(UInt32|int $maxTxSetSize): static
    {
        if (is_int($maxTxSetSize)) {
            $maxTxSetSize = UInt32::of($maxTxSetSize);
        }

        $ledgerUpgrade = new static();
        $ledgerUpgrade->discriminator = LedgerUpgradeType::maxTxSetSize();
        $ledgerUpgrade->value = $maxTxSetSize;

        return $ledgerUpgrade;
    }

    /**
     * Create a new instance by wrapping a base reserve.
     *
     * @param UInt32|int $baseReserve
     * @return static
     */
    public static function wrapBaseReserve(UInt32|int $baseReserve): static
    {
        if (is_int($baseReserve)) {
            $baseReserve = UInt32::of($baseReserve);
        }

        $ledgerUpgrade = new static();
        $ledgerUpgrade->discriminator = LedgerUpgradeType::baseReserve();
        $ledgerUpgrade->value = $baseReserve;

        return $ledgerUpgrade;
    }

    /**
     * Create a new instance by wrapping a set of flags.
     *
     * @param UInt32|int $flags
     * @return static
     */
    public static function wrapFlags(UInt32|int $flags): static
    {
        if (is_int($flags)) {
            $flags = UInt32::of($flags);
        }

        $ledgerUpgrade = new static();
        $ledgerUpgrade->discriminator = LedgerUpgradeType::flags();
        $ledgerUpgrade->value = $flags;

        return $ledgerUpgrade;
    }

    /**
     * Return the underlying value.
     *
     * @return UInt32|null
     */
    public function unwrap(): ?UInt32
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LedgerUpgradeType) {
            return $this->discriminator->getType();
        }

        return null;
    }
}
