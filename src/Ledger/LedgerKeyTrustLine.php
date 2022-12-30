<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\TrustLineAsset;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerKeyTrustLine implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected TrustLineAsset $asset;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The ledger key trust line is missing an account Id');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The ledger key trust line is missing a trust line asset');
        }

        $xdr->write($this->accountId)->write($this->asset);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerKeyTrustLine = new static();
        $ledgerKeyTrustLine->accountId = $xdr->read(AccountId::class);
        $ledgerKeyTrustLine->asset = $xdr->read(TrustLineAsset::class);

        return $ledgerKeyTrustLine;
    }

    /**
     * Get the account Id.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    /**
     * Accept a an account Id.
     *
     * @param AccountId|Addressable|string $accountId
     * @return static
     */
    public function withAccountId(AccountId|Addressable|string $accountId): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = AccountId::fromAddressable($accountId);

        return $clone;
    }

    /**
     * Get the trust line asset.
     *
     * @return TrustLineAsset
     */
    public function getAsset(): TrustLineAsset
    {
        return $this->asset;
    }

    /**
     * Accept a trust line asset.
     *
     * @param TrustLineAsset|Asset|string $asset
     * @return static
     */
    public function withAsset(TrustLineAsset|Asset|string $asset): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->asset = $asset instanceof TrustLineAsset
            ? Copy::deep($asset)
            : TrustLineAsset::fromAsset($asset);

        return $clone;
    }
}
