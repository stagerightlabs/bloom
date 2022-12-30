<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\UInt64;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * @see https://developers.stellar.org/api/resources/accounts/object/
 */
class AccountResource extends Resource
{
    /**
     * Return the links array.
     *
     * @return array<string, string>
     */
    public function getLinks(): array
    {
        $arr = $this->payload->getArray('_links') ?? [];

        return array_reduce(array_keys($arr), function ($carry, $key) use ($arr) {
            $carry[$key] = $arr[$key]['href'];
            return $carry;
        }, []);
    }

    /**
     * Return a single link from the links array.
     *
     * @param string $key
     * @return string|null
     */
    public function getLink(string $key): ?string
    {
        return $this->payload->getString("_links.{$key}.href");
    }

    /**
     * Return the 'self' link.
     *
     * @return string|null
     */
    public function getSelfLink(): ?string
    {
        return $this->getLink('self');
    }

    /**
     * Return the 'transactions' link.
     *
     * @return string|null
     */
    public function getTransactionsLink(): ?string
    {
        return $this->getLink('transactions');
    }

    /**
     * Return the 'operations' link.
     *
     * @return string|null
     */
    public function getOperationsLink(): ?string
    {
        return $this->getLink('operations');
    }

    /**
     * Return the 'payments' link.
     *
     * @return string|null
     */
    public function getPaymentsLink(): ?string
    {
        return $this->getLink('payments');
    }

    /**
     * Return the 'effects' link.
     *
     * @return string|null
     */
    public function getEffectsLink(): ?string
    {
        return $this->getLink('effects');
    }

    /**
     * Return the 'offers' link.
     *
     * @return string|null
     */
    public function getOffersLink(): ?string
    {
        return $this->getLink('offers');
    }

    /**
     * Return the 'trades' link.
     *
     * @return string|null
     */
    public function getTradesLink(): ?string
    {
        return $this->getLink('trades');
    }

    /**
     * Return the 'data' link.
     *
     * @return string|null
     */
    public function getDataLink(): ?string
    {
        return $this->getLink('data');
    }

    /**
     * A unique identifier for the account.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->payload->getString('id');
    }

    /**
     * The account's public key encoded in a base 32 string representation.
     *
     * @return AccountId|null
     */
    public function getAccountId(): ?AccountId
    {
        if ($accountId = $this->payload->getString('account_id')) {
            return AccountId::fromAddressable($accountId);
        }

        return null;
    }

    /**
     * The account's current sequence number.
     *
     * @return SequenceNumber|null
     */
    public function getSequenceNumber(): ?SequenceNumber
    {
        if ($sequenceNumber = $this->payload->getString('sequence')) {
            return SequenceNumber::of($sequenceNumber);
        }

        return null;
    }

    /**
     * The unsigned 32-bit ledger number of the sequence number's age.
     *
     * @return UInt32|null
     */
    public function getSequenceLedger(): ?UInt32
    {
        if ($sequenceLedger = $this->payload->getInteger('sequence_ledger')) {
            return UInt32::of($sequenceLedger);
        }

        return null;
    }

    /**
     * The unsigned 64 bit UNIX timestamp of the sequence number's age.
     *
     * @return UInt64|null
     */
    public function getSequenceTime(): ?UInt64
    {
        if ($sequenceAge = $this->payload->getString('sequence_time')) {
            return UInt64::of($sequenceAge);
        }

        return null;
    }

    /**
     * The number of subentries on the account.
     *
     * @return UInt32|null
     */
    public function getSubEntryCount(): ?UInt32
    {
        if ($subEntryCount = $this->payload->getInteger('subentry_count')) {
            return UInt32::of($subEntryCount);
        }

        return null;
    }

    /**
     * The domain that hosts the account's stellar.toml file.
     *
     * @return string
     */
    public function getHomeDomain(): ?string
    {
        return $this->payload->getString('home_domain');
    }

    /**
     * The sequence number of the last ledger that included changes to this account.
     *
     * @return UInt32|null
     */
    public function getLastModifiedLedgerSequence(): ?UInt32
    {
        if ($ledgerId = $this->payload->getInteger('last_modified_ledger')) {
            return UInt32::of($ledgerId);
        }

        return null;
    }

    /**
     * The number of reserves sponsored by the account.
     *
     * @return UInt32|null
     */
    public function getReservesSponsoringCount(): ?UInt32
    {
        if ($numSponsoring = $this->payload->getInteger('num_sponsoring')) {
            return UInt32::of($numSponsoring);
        }

        return null;
    }

    /**
     * The number of reserves sponsored for the account.
     *
     * @return UInt32|null
     */
    public function getReservesSponsoredCount(): ?UInt32
    {
        if ($numSponsored = $this->payload->getInteger('num_sponsored')) {
            return UInt32::of($numSponsored);
        }

        return null;
    }

    /**
     * The account ID of the sponsor who is paying the reserves for this account.
     *
     * @return string|null
     */
    public function getSponsorId(): ?string
    {
        return $this->payload->getString('sponsor');
    }

    /**
     * The weight required for a valid transaction including the Allow Trust
     * and Bump Sequence operations.
     *
     * @return UInt32
     */
    public function getLowThreshold(): UInt32
    {
        if ($lowThreshold = $this->payload->getInteger('thresholds.low_threshold')) {
            return UInt32::of($lowThreshold);
        }

        return UInt32::of(0);
    }

    /**
     * The weight required for a valid transaction including the Create Account,
     * Payment, Path Payment, Manage Buy Offer, Manage Sell Offer, Create Passive
     * Sell Offer, Change Trust, Inflation, and Manage Data operations.
     *
     * @return UInt32
     */
    public function getMediumThreshold(): UInt32
    {
        if ($mediumThreshold = $this->payload->getInteger('thresholds.med_threshold')) {
            return UInt32::of($mediumThreshold);
        }

        return UInt32::of(0);
    }

    /**
     * The weight required for a valid transaction including the Account Merge
     * and Set Options operations.
     *
     * @return UInt32
     */
    public function getHighThreshold(): UInt32
    {
        if ($highThreshold = $this->payload->getInteger('thresholds.high_threshold')) {
            return UInt32::of($highThreshold);
        }

        return UInt32::of(0);
    }

    /**
     * The flags denoting asset issuer privileges.
     *
     * @return array<int|string, mixed>
     */
    public function getFlags(): array
    {
        if ($flags = $this->payload->getArray('flags')) {
            return $flags;
        }

        return [];
    }

    /**
     * If set to true, no other flags can be changed.
     *
     * @return bool|null
     */
    public function getAuthImmutableFlag(): ?bool
    {
        if ($authImmutable = $this->payload->getBoolean('flags.auth_immutable')) {
            return $authImmutable;
        }

        return null;
    }

    /**
     * If set to true, anyone who wants to hold an asset issued by this account
     * must first be approved by this account.
     *
     * @return bool|null
     */
    public function getAuthRequiredFlag(): ?bool
    {
        if ($authRequired = $this->payload->getBoolean('flags.auth_required')) {
            return $authRequired;
        }

        return null;
    }

    /**
     * If set to true, this account can freeze the balance of a holder of an
     * asset issued by this account.
     *
     * @return bool|null
     */
    public function getAuthRevocableFlag(): ?bool
    {
        if ($authRevocable = $this->payload->getBoolean('flags.auth_revocable')) {
            return $authRevocable;
        }

        return null;
    }

    /**
     * If set to true, trustlines created for assets issued bu this account
     * have clawbacks enabled.
     *
     * @return bool|null
     */
    public function getAuthClawbackEnabledFlag(): ?bool
    {
        if ($authClawbackEnabled = $this->payload->getBoolean('flags.auth_clawback_enabled')) {
            return $authClawbackEnabled;
        }

        return null;
    }

    /**
     * The assets this account holds.
     *
     * @return array<AccountBalanceResource>
     */
    public function getBalances(): array
    {
        if ($balances = $this->payload->getArray('balances')) {
            return array_map(function ($balance) {
                return AccountBalanceResource::fromArray($balance);
            }, $balances);
        }

        return [];
    }

    /**
     * Find the balance of a given asset held by this account.
     *
     * @param Asset|string $asset
     * @return AccountBalanceResource|null
     */
    public function getBalanceForAsset(Asset|string $asset): AccountBalanceResource|null
    {
        if (is_string($asset)) {
            $asset = Asset::fromNativeString($asset);
        }

        if ($balances = $this->payload->getArray('balances')) {
            foreach ($balances as $balance) {
                if ($balance['asset_type'] == 'native' && $asset->isNative()) {
                    return AccountBalanceResource::fromArray($balance);
                }

                if (
                    array_key_exists('asset_code', $balance)
                    && $asset->getAssetCode() == $balance['asset_code']
                    && $asset->getIssuerAddress() == $balance['asset_issuer']
                ) {
                    return AccountBalanceResource::fromArray($balance);
                }
            }
        }

        return null;
    }

    /**
     * The public keys and associated weights that can be used to authorize
     * transactions for this account. Used for multi-sig.
     *
     * @return array<AccountSignerResource>
     */
    public function getSigners(): array
    {
        if ($signers = $this->payload->getArray('signers')) {
            return array_map(function ($signer) {
                return AccountSignerResource::fromArray($signer);
            }, $signers);
        }

        return [];
    }

    /**
     * An array of account data fields.
     *
     * @param string|null $key
     * @return array<int|string, mixed>|string|null
     */
    public function getData(string $key = null): array|string|null
    {
        if (!$data = $this->payload->getArray('data')) {
            return [];
        }

        if ($key) {
            return array_key_exists($key, $data)
                ? $data[$key]
                : null;
        }

        return $data;
    }
}
