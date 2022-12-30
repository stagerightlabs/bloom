<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class LedgerEntryData extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LedgerEntryType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LedgerEntryType::ACCOUNT           => AccountEntry::class,
            LedgerEntryType::TRUSTLINE         => TrustLineEntry::class,
            LedgerEntryType::OFFER             => OfferEntry::class,
            LedgerEntryType::DATA              => DataEntry::class,
            LedgerEntryType::CLAIMABLE_BALANCE => ClaimableBalanceEntry::class,
            LedgerEntryType::LIQUIDITY_POOL    => LiquidityPoolEntry::class,
        ];
    }

    /**
     * Create a new instance by wrapping an Account Entry.
     *
     * @param AccountEntry $accountEntry
     * @return static
     */
    public static function wrapAccountEntry(AccountEntry $accountEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::account();
        $ledgerEntryData->value = $accountEntry;

        return $ledgerEntryData;
    }

    /**
     * Create a new instance by wrapping a trust line entry.
     *
     * @param TrustLineEntry $trustLineEntry
     * @return static
     */
    public static function wrapTrustLineEntry(TrustLineEntry $trustLineEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::trustline();
        $ledgerEntryData->value = $trustLineEntry;

        return $ledgerEntryData;
    }

    /**
     * Create a new instance by wrapping an offer entry.
     *
     * @param OfferEntry $offerEntry
     * @return static
     */
    public static function wrapOfferEntry(OfferEntry $offerEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::offer();
        $ledgerEntryData->value = $offerEntry;

        return $ledgerEntryData;
    }

    /**
     * Create a new instance by wrapping a data entry.
     *
     * @param DataEntry $dataEntry
     * @return static
     */
    public static function wrapDataEntry(DataEntry $dataEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::data();
        $ledgerEntryData->value = $dataEntry;

        return $ledgerEntryData;
    }

    /**
     * Create a new instance by wrapping a claimable balance entry.
     *
     * @param ClaimableBalanceEntry $claimableBalanceEntry
     * @return static
     */
    public static function wrapClaimableBalanceEntry(ClaimableBalanceEntry $claimableBalanceEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::claimableBalance();
        $ledgerEntryData->value = $claimableBalanceEntry;

        return $ledgerEntryData;
    }

    /**
     * Create a new instance by wrapping a liquidity pool entry.
     *
     * @param LiquidityPoolEntry $liquidityPoolEntry
     * @return static
     */
    public static function wrapLiquidityPoolEntry(LiquidityPoolEntry $liquidityPoolEntry): static
    {
        $ledgerEntryData = new static();
        $ledgerEntryData->discriminator = LedgerEntryType::liquidityPool();
        $ledgerEntryData->value = $liquidityPoolEntry;

        return $ledgerEntryData;
    }

    /**
     * Return the underlying value.
     *
     * @return AccountEntry|TrustLineEntry|OfferEntry|DataEntry|ClaimableBalanceEntry|LiquidityPoolEntry|null
     */
    public function unwrap(): AccountEntry|TrustLineEntry|OfferEntry|DataEntry|ClaimableBalanceEntry|LiquidityPoolEntry|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
