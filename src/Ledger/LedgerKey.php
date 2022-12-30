<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class LedgerKey extends Union implements XdrUnion
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
            LedgerEntryType::ACCOUNT           => LedgerKeyAccount::class,
            LedgerEntryType::TRUSTLINE         => LedgerKeyTrustLine::class,
            LedgerEntryType::OFFER             => LedgerKeyOffer::class,
            LedgerEntryType::DATA              => LedgerKeyData::class,
            LedgerEntryType::CLAIMABLE_BALANCE => LedgerKeyClaimableBalance::class,
            LedgerEntryType::LIQUIDITY_POOL    => LedgerKeyLiquidityPool::class,
        ];
    }

    /**
     * Create a new instance by wrapping a LedgerKeyAccount.
     *
     * @param LedgerKeyAccount $ledgerKeyAccount
     * @return static
     */
    public static function wrapLedgerKeyAccount(LedgerKeyAccount $ledgerKeyAccount): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::account();
        $ledgerKey->value = $ledgerKeyAccount;

        return $ledgerKey;
    }

    /**
     * Create a new instance by wrapping a LedgerKeyTrustLine.
     *
     * @param LedgerKeyTrustLine $ledgerKeyTrustLine
     * @return static
     */
    public static function wrapLedgerKeyTrustLine(LedgerKeyTrustLine $ledgerKeyTrustLine): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::trustline();
        $ledgerKey->value = $ledgerKeyTrustLine;

        return $ledgerKey;
    }

    /**
     * Create a new instance by wrapping a LedgerKeyOffer.
     *
     * @param LedgerKeyOffer $ledgerKeyOffer
     * @return static
     */
    public static function wrapLedgerKeyOffer(LedgerKeyOffer $ledgerKeyOffer): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::offer();
        $ledgerKey->value = $ledgerKeyOffer;

        return $ledgerKey;
    }

    /**
     * Create a new instance by wrapping a LedgerKeyData.
     *
     * @param LedgerKeyData $ledgerKeyData
     * @return static
     */
    public static function wrapLedgerKeyData(LedgerKeyData $ledgerKeyData): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::data();
        $ledgerKey->value = $ledgerKeyData;

        return $ledgerKey;
    }

    /**
     * Create a new instance by wrapping a LedgerKeyClaimableBalance.
     *
     * @param LedgerKeyClaimableBalance $ledgerKeyClaimableBalance
     * @return static
     */
    public static function wrapLedgerKeyClaimableBalance(LedgerKeyClaimableBalance $ledgerKeyClaimableBalance): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::claimableBalance();
        $ledgerKey->value = $ledgerKeyClaimableBalance;

        return $ledgerKey;
    }

    /**
     * Create a new instance by wrapping a LedgerKeyLiquidityPool.
     *
     * @param LedgerKeyLiquidityPool $ledgerKeyLiquidityPool
     * @return static
     */
    public static function wrapLedgerKeyLiquidityPool(LedgerKeyLiquidityPool $ledgerKeyLiquidityPool): static
    {
        $ledgerKey = new static();
        $ledgerKey->discriminator = LedgerEntryType::liquidityPool();
        $ledgerKey->value = $ledgerKeyLiquidityPool;

        return $ledgerKey;
    }

    /**
     * Return the underlying value.
     *
     * @return LedgerKeyAccount|LedgerKeyTrustLine|LedgerKeyOffer|LedgerKeyData|LedgerKeyClaimableBalance|LedgerKeyLiquidityPool|null
     */
    public function unwrap(): LedgerKeyAccount|LedgerKeyTrustLine|LedgerKeyOffer|LedgerKeyData|LedgerKeyClaimableBalance|LedgerKeyLiquidityPool|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new instance that represents an account ledger key.
     *
     * @param AccountId|Addressable|string $address
     * @return static
     */
    public static function account(AccountId|Addressable|string $address): static
    {
        $ledgerKeyAccount = (new LedgerKeyAccount())->withAccountId($address);
        return self::wrapLedgerKeyAccount($ledgerKeyAccount);
    }

    /**
     * Create a new instance that represents a trust line ledger key.
     *
     * @param AccountId|Addressable|string $accountId
     * @param TrustLineAsset|Asset|string $asset
     * @return static
     */
    public static function trustLine(
        AccountId|Addressable|string $accountId,
        TrustLineAsset|Asset|string $asset
    ): static {
        $ledgerKeyTrustLine = (new LedgerKeyTrustLine())
            ->withAccountId($accountId)
            ->withAsset($asset);

        return self::wrapLedgerKeyTrustLine($ledgerKeyTrustLine);
    }

    /**
     * Create a new instance that represents an offer ledger key.
     *
     * @param AccountId|Addressable|string $sellerId
     * @param Int64|int $offerId
     * @return static
     */
    public static function offer(
        AccountId|Addressable|string $sellerId,
        Int64|int $offerId,
    ): static {
        $ledgerKeyOffer = (new LedgerKeyOffer())
            ->withSellerId($sellerId)
            ->withOfferId($offerId);

        return self::wrapLedgerKeyOffer($ledgerKeyOffer);
    }

    /**
     * Create a new instance that represents a data ledger key.
     *
     * @param AccountId|Addressable|string $accountId
     * @param String64|string $dataName
     * @return static
     */
    public static function data(
        AccountId|Addressable|string $accountId,
        String64|string $dataName
    ): static {
        $ledgerKeyData = (new LedgerKeyData())
            ->withAccountId($accountId)
            ->withDataName($dataName);

        return self::wrapLedgerKeyData($ledgerKeyData);
    }

    /**
     * Create a new instance that represents a claimable balance ledger key.
     *
     * @param ClaimableBalanceId|Hash|string $balanceId
     * @return static
     */
    public static function claimableBalance(
        ClaimableBalanceId|Hash|string $balanceId
    ): static {
        $ledgerKeyClaimableBalance = (new LedgerKeyClaimableBalance())
            ->withClaimableBalanceId($balanceId);

        return self::wrapLedgerKeyClaimableBalance($ledgerKeyClaimableBalance);
    }

    /**
     * Create a new instance that represents a liquidity pool ledger key.
     *
     * @param PoolId|string $poolId
     * @return static
     */
    public static function liquidityPool(
        PoolId|string $poolId
    ): static {
        $ledgerKeyLiquidityPool = (new LedgerKeyLiquidityPool())
            ->withLiquidityPoolId($poolId);

        return self::wrapLedgerKeyLiquidityPool($ledgerKeyLiquidityPool);
    }
}
