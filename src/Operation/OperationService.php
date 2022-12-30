<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\MuxedAccount;
use StageRightLabs\Bloom\Account\Signer;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Asset\AssetCode;
use StageRightLabs\Bloom\Asset\PathPaymentAssetList;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\ClaimableBalance\Claimant;
use StageRightLabs\Bloom\ClaimableBalance\ClaimantList;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidAssetException;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Ledger\Price;
use StageRightLabs\Bloom\LiquidityPool\LiquidityPoolParameters;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Primitives\String32;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Service;
use StageRightLabs\Bloom\Transaction\SequenceNumber;

/**
 * The method and parameter descriptions below are pulled from the Stellar
 * documentation, with additional notes as necessary.
 * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations
 */
final class OperationService extends Service
{
    /**
     * Creates and funds a new account with the specified starting balance.
     *
     * A string passed as the starting balance will be read as a scaled amount;
     * an integer as a descaled value.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-account
     * @param Addressable|string $destination Account address that is created and funded.
     * @param Int64|ScaledAmount|int|string $startingBalance Amount of XLM to send to the newly created account. This XLM comes from the source account.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function createAccount(
        Addressable|string $destination,
        Int64|ScaledAmount|int|string $startingBalance,
        Addressable|string $source = null,
    ): Operation {
        return CreateAccountOp::operation($destination, $startingBalance, $source);
    }

    /**
     * Sends an amount in a specific asset to a destination account.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#payment
     * @param Addressable|string $destination Account address that receives the payment.
     * @param Asset $asset Asset to send to the destination account.
     * @param Int64|ScaledAmount|integer|string $amount Amount of the aforementioned asset to send.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     * @throws InvalidAssetException
     */
    public function payment(
        Addressable|string $destination,
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string|null $source = null,
    ): Operation {
        return PaymentOp::operation($destination, $asset, $amount, $source);
    }

    /**
     * A payment where the asset sent can be different than the asset received;
     * allows the user to specify the amount of the asset to send.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-send
     * @param Asset|string $sendingAsset The asset deducted from the sender's account.
     * @param Int64|ScaledAmount|integer|string $sendingAmount The amount of send asset to deduct (excluding fees).
     * @param MuxedAccount|AccountId|Addressable|string $destination Account ID of the recipient.
     * @param Asset|string $destinationAsset The asset the destination account receives.
     * @param Int64|ScaledAmount|integer|string $destinationMinimum The minimum amount of destination asset the destination account can receive.
     * @param PathPaymentAssetList|array<Asset|string> $path The assets (other than send asset and destination asset) involved in the offers the path takes. For example, if you can only find a path from USD to EUR through XLM and BTC, the path would be USD -> XLM -> BTC -> EUR and the path field would contain XLM and BTC.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function pathPaymentStrictSend(
        Asset|string $sendingAsset,
        Int64|ScaledAmount|int|string $sendingAmount,
        MuxedAccount|AccountId|Addressable|string $destination,
        Asset|string $destinationAsset,
        Int64|ScaledAmount|int|string $destinationMinimum,
        PathPaymentAssetList|array $path = [],
        Addressable|string $source = null,
    ): Operation {
        return PathPaymentStrictSendOp::operation($sendingAsset, $sendingAmount, $destination, $destinationAsset, $destinationMinimum, $path, $source);
    }

    /**
     * A payment where the asset sent can be different than the asset received;
     * allows the user to specify the amount of the asset to send.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-receive
     * @param Asset|string $sendingAsset The asset deducted from the sender's account.
     * @param Int64|ScaledAmount|integer|string $sendingMaximum The maximum amount of send asset to deduct (excluding fees).
     * @param MuxedAccount|AccountId|Addressable|string $destination Account ID of the recipient.
     * @param Asset|string $destinationAsset The asset the destination account receives.
     * @param Int64|ScaledAmount|integer|string $destinationAmount The amount of destination asset the destination account receives.
     * @param PathPaymentAssetList|array<Asset|string> $path The assets (other than send asset and destination asset) involved in the offers the path takes. For example, if you can only find a path from USD to EUR through XLM and BTC, the path would be USD -> XLM -> BTC -> EUR and the path field would contain XLM and BTC.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function pathPaymentStrictReceive(
        Asset|string $sendingAsset,
        Int64|ScaledAmount|int|string $sendingMaximum,
        MuxedAccount|AccountId|Addressable|string $destination,
        Asset|string $destinationAsset,
        Int64|ScaledAmount|int|string $destinationAmount,
        PathPaymentAssetList|array $path = [],
        Addressable|string $source = null,
    ): Operation {
        return PathPaymentStrictReceiveOp::operation($sendingAsset, $sendingMaximum, $destination, $destinationAsset, $destinationAmount, $path, $source);
    }

    /**
     * Creates, updates, or deletes an offer to buy a specific amount of an asset for another.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-buy-offer
     * @param Asset|string $sellingAsset Asset the offer creator is selling.
     * @param Asset|string $buyingAsset Asset the offer creator is buying.
     * @param Int64|ScaledAmount|integer|string $buyingAmount Amount of buying being bought. Set to 0 if you want to delete an existing offer.
     * @param Price|string $price Price of 1 unit of buying in terms of selling. For example, if you wanted to buy 30 XLM and sell 5 BTC, the price would be Price::of(5,30).
     * @param Int64|integer|null $offerId The ID of the offer. Set to existing offer ID to update or delete. Set to zero or omit for a new offer.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function manageBuyOffer(
        Asset|string $sellingAsset,
        Asset|string $buyingAsset,
        Int64|ScaledAmount|int|string $buyingAmount,
        Price|string $price,
        Int64|int $offerId = null,
        Addressable|string $source = null,
    ): Operation {
        return ManageBuyOfferOp::operation($sellingAsset, $buyingAsset, $buyingAmount, $price, $offerId, $source);
    }

    /**
     * Creates, updates, or deletes an offer to sell a specific amount of an
     * asset for another.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-sell-offer
     * @param Asset|string $sellingAsset Asset the offer creator is selling.
     * @param Int64|ScaledAmount|integer|string $sellingAmount Asset the offer creator is buying.
     * @param Asset|string $buyingAsset Amount of selling being sold. Set to 0 if you want to delete an existing offer.
     * @param Price|string $price Price of 1 unit of selling in terms of buying. For example, if you wanted to sell 30 XLM and buy 5 BTC, the price would be Price::of(5,30);.
     * @param Int64|integer|null $offerId
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function manageSellOffer(
        Asset|string $sellingAsset,
        Int64|ScaledAmount|int|string $sellingAmount,
        Asset|string $buyingAsset,
        Price|string $price,
        Int64|int $offerId = null,
        Addressable|string|null $source = null,
    ): Operation {
        return ManageSellOfferOp::operation($sellingAsset, $sellingAmount, $buyingAsset, $price, $offerId, $source);
    }

    /**
     * Creates an offer to sell one asset for another without taking a reverse offer of equal price.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-passive-sell-offer
     * @param Asset|string $sellingAsset Asset the offer creator is selling.
     * @param Asset|string $buyingAsset Asset the offer creator is buying.
     * @param Price|string $price Amount of selling being sold.
     * @param Int64|ScaledAmount|integer|string $amount Price of 1 unit of selling in terms of buying. For example, if you wanted to sell 30 XLM and buy 5 BTC, the price would be Price::of(5,30).
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function createPassiveSellOffer(
        Asset|string $sellingAsset,
        Asset|string $buyingAsset,
        Price|string $price,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string $source = null,
    ): Operation {
        return CreatePassiveSellOfferOp::operation($sellingAsset, $buyingAsset, $price, $amount, $source);
    }

    /**
     * Set options for an account such as flags, inflation destination, signers,
     * home domain, and master key weight
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-options
     * @param AccountId|null $inflationDestination Account of the inflation destination.
     * @param UInt32|integer|null $clearFlags Indicates which flags to clear. For details about the flags, please refer to the Accounts section of the Stellar documentation. The bit mask integer subtracts from the existing flags of the account. This allows for setting specific bits without knowledge of existing flags.
     * @param UInt32|integer|null $setFlags Indicates which flags to set. For details about the flags, please refer to the Accounts section of the Stellar documentation. The bit mask integer adds onto the existing flags of the account. This allows for setting specific bits without knowledge of existing flags.
     * @param UInt32|integer|null $masterWeight A number from 0-255 (inclusive) representing the weight of the master key. If the weight of the master key is updated to 0, it is effectively disabled.
     * @param UInt32|integer|null $lowThreshold A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a low threshold.
     * @param UInt32|integer|null $mediumThreshold A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a medium threshold.
     * @param UInt32|integer|null $highThreshold A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a high threshold.
     * @param String32|string|null $homeDomain Sets the home domain of an account. See the Federation section of the Stellar documentation.
     * @param Signer|null $signer Add, update, or remove a signer from an account. Signer weight is a number from 0-255 (inclusive). The signer is deleted if the weight is 0.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function setOptions(
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
        return SetOptionsOp::operation($inflationDestination, $clearFlags, $setFlags, $masterWeight, $lowThreshold, $mediumThreshold, $highThreshold, $homeDomain, $signer, $source);
    }

    /**
     * Creates, updates, or deletes a trustline.
     *
     * @param ChangeTrustAsset|LiquidityPoolParameters|Asset|string $asset The asset of the trustline. For example, if a user extends a trustline of up to 200 USD to an anchor, the line is USD:anchor.
     * @param Int64|ScaledAmount|integer|string|null $limit The limit of the trustline.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function changeTrust(
        ChangeTrustAsset|LiquidityPoolParameters|Asset|string $asset,
        Int64|ScaledAmount|int|string $limit = null,
        Addressable|string $source = null,
    ): Operation {
        return ChangeTrustOp::operation($asset, $limit, $source);
    }

    /**
     * Updates the authorized flag of an existing trustline.
     *
     * This operation is deprecated as of Protocol 17- prefer SetTrustlineFlags instead.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#allow-trust
     * @param AccountId|Addressable|string $trustor The account of the recipient of the trustline.
     * @param AssetCode|string $assetCode The 4 or 12 character-maximum asset code of the trustline the source account is authorizing. For example, if an issuing account wants to allow another account to hold its USD credit, the type is USD.
     * @param UInt32|integer $authorize Flag indicating whether the trustline is authorized. 1 if the account is authorized to transact with the asset. 2 if the account is authorized to maintain offers, but not to perform other transactions
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function allowTrust(
        AccountId|Addressable|string $trustor,
        AssetCode|string $assetCode,
        UInt32|int $authorize,
        Addressable|string $source = null
    ): Operation {
        return AllowTrustOp::operation($trustor, $assetCode, $authorize, $source);
    }

    /**
     * Transfers the XLM balance of an account to another account and removes
     * the source account from the ledger.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#account-merge
     * @param Addressable|string $destination The account that receives the remaining XLM balance of the source account.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function accountMerge(
        Addressable|string $destination,
        Addressable|string $source = null
    ): Operation {
        return AccountMergeOp::operation($destination, $source);
    }

    /**
     * Sets, modifies, or deletes a data entry (name/value pair) that is attached to an account.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-data
     * @param String64|string $name String up to 64 bytes long. If this is a new Name it will add the given name/value pair to the account. If this Name is already present then the associated value will be modified.
     * @param DataValue|string|null $value Optional. If not present then the existing Name will be deleted. If present then this value will be set in the DataEntry. Up to 64 bytes long.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function manageData(
        String64|string $name,
        DataValue|string $value = null,
        Addressable|string $source = null
    ): Operation {
        return ManageDataOp::operation($name, $value, $source);
    }

    /**
     * Bumps forward the sequence number of the source account to the given sequence
     * number, invalidating any transaction with a smaller sequence number.
     *
     * @param SequenceNumber|Int64|integer $bumpTo The desired value for the operation's source account sequence number.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function bumpSequence(
        SequenceNumber|Int64|int $bumpTo,
        Addressable|string $source = null
    ): Operation {
        return BumpSequenceOp::operation($bumpTo, $source);
    }

    /**
     * Moves an amount of asset from the operation source account into a new ClaimableBalanceEntry.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-claimable-balance
     * @param Asset|string $asset Asset that will be held in the ClaimableBalanceEntry in the form asset_code:issuing_address or native (XLM).
     * @param Int64|ScaledAmount|integer|string $amount Amount of asset stored in the ClaimableBalanceEntry.
     * @param ClaimantList|Claimant|array<Claimant|string> $claimants List of Claimants (account address and ClaimPredicate pair) that can claim this ClaimableBalanceEntry.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function createClaimableBalance(
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        ClaimantList|Claimant|array $claimants,
        Addressable|string $source = null,
    ): Operation {
        return CreateClaimableBalanceOp::operation($asset, $amount, $claimants, $source);
    }

    /**
     * Claims a ClaimableBalanceEntry that corresponds to the BalanceID and adds
     * the amount of an asset on the entry to the source account.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#claim-claimable-balance
     * @param ClaimableBalanceId|Hash|string $balanceId BalanceID on the ClaimableBalanceEntry that the source account is claiming. The balanceID can be retrieved from a successful CreateClaimableBalanceResult.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function claimClaimableBalance(
        ClaimableBalanceId|Hash|string $balanceId,
        Addressable|string $source = null,
    ): Operation {
        return ClaimClaimableBalanceOp::operation($balanceId, $source);
    }

    /**
     * Allows an account to pay the base reserves for another account; sponsoring
     * account establishes the is-sponsoring-future-reserves relationship. There
     * must also be an end sponsoring future reserves operation in the same transaction
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#begin-sponsoring-future-reserves
     * @param Addressable|string $sponsoredId Account that will have its reserves sponsored.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function beginSponsoringFutureReserves(
        Addressable|string $sponsoredId,
        Addressable|string $source = null,
    ): Operation {
        return BeginSponsoringFutureReservesOp::operation($sponsoredId, $source);
    }

    /**
     * Terminates the current is-sponsoring-future-reserves relationship in
     * which the source account is sponsored.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#end-sponsoring-future-reserves
     * @param Addressable|string $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function endSponsoringFutureReserves(Addressable|string $source): Operation
    {
        return EndSponsoringFutureReservesOp::operation($source);
    }

    /**
     * Revoke an account sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeAccountSponsorship
     * @param Addressable|string $address
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeAccountSponsorship(
        Addressable|string $address,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::account($address),
            source: $source,
        );
    }

    /**
     * Revoke a trust line sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeTrustlineSponsorship
     * @param Addressable|string $address
     * @param Asset|string $asset
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeTrustLineSponsorship(
        Addressable|string $address,
        Asset|string $asset,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::trustLine($address, $asset),
            source: $source,
        );
    }

    /**
     * Revoke an offer sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeOfferSponsorship
     * @param Addressable|string $sellerId
     * @param Int64|integer $offerId
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeOfferSponsorship(
        Addressable|string $sellerId,
        Int64|int $offerId,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::offer($sellerId, $offerId),
            source: $source,
        );
    }

    /**
     * Revoke a data sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeDataSponsorship
     * @param Addressable|string $address
     * @param String64|string $dataName
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeDataSponsorship(
        Addressable|string $address,
        String64|string $dataName,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::data($address, $dataName),
            source: $source,
        );
    }

    /**
     * Revoke a claimable balance sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeClaimableBalanceSponsorship
     * @param ClaimableBalanceId|Hash|string $balanceId
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeClaimableBalanceSponsorship(
        ClaimableBalanceId|Hash|string $balanceId,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::claimableBalance($balanceId),
            source: $source,
        );
    }

    /**
     * Revoke a liquidity pool sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship
     * @param PoolId|string $poolId
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeLiquidityPoolSponsorship(
        PoolId|string $poolId,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            ledgerKey: LedgerKey::liquidityPool($poolId),
            source: $source,
        );
    }

    /**
     * Revoke a signer sponsorship.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @see https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship
     * @param Addressable|string $address
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function revokeSignerSponsorship(
        Addressable|string $address,
        Addressable|string $source = null,
    ): Operation {
        return RevokeSponsorshipOp::operation(
            signer: RevokeSponsorshipOpSigner::fromAddressable($address),
            source: $source
        );
    }

    /**
     * Burns an amount in a specific asset from a receiving account.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#clawback
     * @param Addressable|string $from Account address that receives the clawback.
     * @param Asset|string $asset Asset held by the destination account.
     * @param Int64|ScaledAmount|integer|string $amount Amount of the aforementioned asset to burn.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function clawback(
        Addressable|string $from,
        Asset|string $asset,
        Int64|ScaledAmount|int|string $amount,
        Addressable|string $source = null,
    ): Operation {
        return ClawbackOp::operation(
            asset: $asset,
            from: $from,
            amount: $amount,
            source: $source,
        );
    }

    /**
     * Claws back an unclaimed ClaimableBalanceEntry, burning the pending
     * amount of the asset.
     *
     * @param ClaimableBalanceId|Hash|string $balanceId The BalanceID on the ClaimableBalanceEntry that the source account is claiming, which can be retrieved from a successful CreateClaimableBalanceResult
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function clawbackClaimableBalance(
        ClaimableBalanceId|Hash|string $balanceId,
        Addressable|string $source = null,
    ): Operation {
        return ClawbackClaimableBalanceOp::operation(
            balanceId: $balanceId,
            source: $source,
        );
    }

    /**
     * Allows issuing account to configure authorization and trustline flags to an asset.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-trustline-flags
     * @param AccountId|Addressable|string $trustor The account that established this trustline.
     * @param Asset|string $asset The asset trustline whose flags are being modified.
     * @param boolean|null $authorized Optional. Toggle the 'authorized' flag.
     * @param boolean|null $authorizedToMaintainLiabilities Optional. Toggle the 'authorizedToMaintainLiabilities' flag.
     * @param boolean|null $clawbackEnabled Optional. Toggle the 'clawbackEnabled' flag.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function setTrustLineFlags(
        AccountId|Addressable|string $trustor,
        Asset|string $asset,
        bool $authorized = null,
        bool $authorizedToMaintainLiabilities = null,
        bool $clawbackEnabled = null,
        Addressable|string $source = null,
    ): Operation {
        return SetTrustLineFlagsOp::operation(
            trustor: $trustor,
            asset: $asset,
            authorized: $authorized,
            authorizedToMaintainLiabilities: $authorizedToMaintainLiabilities,
            clawbackEnabled: $clawbackEnabled,
            source: $source,
        );
    }

    /**
     * Deposits assets into a liquidity pool, increasing the reserves of a
     * liquidity pool in exchange for pool shares.
     *
     * Parameters to this operation depend on the ordering of assets in the
     * liquidity pool: “A” refers to the first asset in the liquidity pool,
     * and “B” refers to the second asset in the liquidity pool.
     *
     * If the pool is empty, then this operation deposits maxAmountA of A and
     * maxAmountB of B into the pool. If the pool is not empty, then this
     * operation deposits at most maxAmountA of A and maxAmountB of B
     * into the pool. The actual amounts deposited are determined
     * using the current reserves of the pool. You can use these
     * parameters to control a percentage of slippage.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-deposit
     * @param PoolId|string $poolId The PoolID for the Liquidity Pool to deposit into.
     * @param Int64|ScaledAmount|int|string $maxAmountA Maximum amount of first asset to deposit.
     * @param Int64|ScaledAmount|int|string $maxAmountB Maximum amount of second asset to deposit.
     * @param Price|string $minPrice Minimum depositA/depositB.
     * @param Price|string $maxPrice Maximum depositA/depositB.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function liquidityPoolDeposit(
        PoolId|string $poolId,
        Int64|ScaledAmount|int|string $maxAmountA,
        Int64|ScaledAmount|int|string $maxAmountB,
        Price|string $minPrice,
        Price|string $maxPrice,
        Addressable|string $source = null,
    ): Operation {
        return LiquidityPoolDepositOp::operation(
            poolId: $poolId,
            maxAmountA: $maxAmountA,
            maxAmountB: $maxAmountB,
            minPrice: $minPrice,
            maxPrice: $maxPrice,
            source: $source,
        );
    }

    /**
     * Withdraw assets from a liquidity pool, reducing the number of pool
     * shares in exchange for reserves of a liquidity pool.
     *
     * The minAmountA and minAmountB parameters can be used to control
     * a percentage of slippage from the "spot price" on the pool.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-withdraw
     * @param PoolId|string $poolId The PoolID for the Liquidity Pool to withdraw from.
     * @param Int64|ScaledAmount|int|string $amount Amount of pool shares to withdraw.
     * @param Int64|ScaledAmount|int|string $minAmountA Minimum amount of the first asset to withdraw.
     * @param Int64|ScaledAmount|int|string $minAmountB Minimum amount of the second asset to withdraw.
     * @param Addressable|string|null $source Optional. Defaults to the transaction's source account.
     * @return Operation
     */
    public function liquidityPoolWithdraw(
        PoolId|string $poolId,
        Int64|ScaledAmount|int|string $amount,
        Int64|ScaledAmount|int|string $minAmountA,
        Int64|ScaledAmount|int|string $minAmountB,
        Addressable|string $source = null,
    ): Operation {
        return LiquidityPoolWithdrawOp::operation(
            poolId: $poolId,
            amount: $amount,
            minAmountA: $minAmountA,
            minAmountB: $minAmountB,
            source: $source,
        );
    }
}
