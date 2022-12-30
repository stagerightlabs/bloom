# Operations 

The method and parameter descriptions below are pulled from the Stellar
documentation, with additional notes as necessary.

Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations)

## createAccount()

Creates and funds a new account with the specified starting balance.

A string passed as the starting balance will be read as a scaled amount;
an integer as a descaled value.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $destination| `Addressable,string` | Account address that is created and funded. |
| $startingBalance| `Int64,ScaledAmount,int,string` | Amount of XLM to send to the newly created account. This XLM comes from the source account. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-account](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-account)

## payment()

Sends an amount in a specific asset to a destination account.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $destination| `Addressable,string` | Account address that receives the payment. |
| $asset| `Asset` | Asset to send to the destination account. |
| $amount| `Int64,ScaledAmount,integer,string` | Amount of the aforementioned asset to send. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#payment](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#payment)

## pathPaymentStrictSend()

A payment where the asset sent can be different than the asset received;
allows the user to specify the amount of the asset to send.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sendingAsset| `Asset,string` | The asset deducted from the sender's account. |
| $sendingAmount| `Int64,ScaledAmount,integer,string` | The amount of send asset to deduct (excluding fees). |
| $destination| `MuxedAccount,AccountId,Addressable,string` | Account ID of the recipient. |
| $destinationAsset| `Asset,string` | The asset the destination account receives. |
| $destinationMinimum| `Int64,ScaledAmount,integer,string` | The minimum amount of destination asset the destination account can receive. |
| $path| `PathPaymentAssetList,array<Asset,string>` | The assets (other than send asset and destination asset) involved in the offers the path takes. For example, if you can only find a path from USD to EUR through XLM and BTC, the path would be USD -> XLM -> BTC -> EUR and the path field would contain XLM and BTC. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-send](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-send)

## pathPaymentStrictReceive()

A payment where the asset sent can be different than the asset received;
allows the user to specify the amount of the asset to send.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sendingAsset| `Asset,string` | The asset deducted from the sender's account. |
| $sendingMaximum| `Int64,ScaledAmount,integer,string` | The maximum amount of send asset to deduct (excluding fees). |
| $destination| `MuxedAccount,AccountId,Addressable,string` | Account ID of the recipient. |
| $destinationAsset| `Asset,string` | The asset the destination account receives. |
| $destinationAmount| `Int64,ScaledAmount,integer,string` | The amount of destination asset the destination account receives. |
| $path| `PathPaymentAssetList,array<Asset,string>` | The assets (other than send asset and destination asset) involved in the offers the path takes. For example, if you can only find a path from USD to EUR through XLM and BTC, the path would be USD -> XLM -> BTC -> EUR and the path field would contain XLM and BTC. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-receive](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-receive)

## manageBuyOffer()

Creates, updates, or deletes an offer to buy a specific amount of an asset for another.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sellingAsset| `Asset,string` | Asset the offer creator is selling. |
| $buyingAsset| `Asset,string` | Asset the offer creator is buying. |
| $buyingAmount| `Int64,ScaledAmount,integer,string` | Amount of buying being bought. Set to 0 if you want to delete an existing offer. |
| $price| `Price,string` | Price of 1 unit of buying in terms of selling. For example, if you wanted to buy 30 XLM and sell 5 BTC, the price would be Price::of(5,30). |
| $offerId| `Int64,integer,null` | The ID of the offer. Set to existing offer ID to update or delete. Set to zero or omit for a new offer. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-buy-offer](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-buy-offer)

## manageSellOffer()

Creates, updates, or deletes an offer to sell a specific amount of an
asset for another.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sellingAsset| `Asset,string` | Asset the offer creator is selling. |
| $sellingAmount| `Int64,ScaledAmount,integer,string` | Asset the offer creator is buying. |
| $buyingAsset| `Asset,string` | Amount of selling being sold. Set to 0 if you want to delete an existing offer. |
| $price| `Price,string` | Price of 1 unit of selling in terms of buying. For example, if you wanted to sell 30 XLM and buy 5 BTC, the price would be Price::of(5,30);. |
| $offerId| `Int64,integer,null` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-sell-offer](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-sell-offer)

## createPassiveSellOffer()

Creates an offer to sell one asset for another without taking a reverse offer of equal price.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sellingAsset| `Asset,string` | Asset the offer creator is selling. |
| $buyingAsset| `Asset,string` | Asset the offer creator is buying. |
| $price| `Price,string` | Amount of selling being sold. |
| $amount| `Int64,ScaledAmount,integer,string` | Price of 1 unit of selling in terms of buying. For example, if you wanted to sell 30 XLM and buy 5 BTC, the price would be Price::of(5,30). |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-passive-sell-offer](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-passive-sell-offer)

## setOptions()

Set options for an account such as flags, inflation destination, signers,
home domain, and master key weight

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $inflationDestination| `AccountId,null` | Account of the inflation destination. |
| $clearFlags| `UInt32,integer,null` | Indicates which flags to clear. For details about the flags, please refer to the Accounts section of the Stellar documentation. The bit mask integer subtracts from the existing flags of the account. This allows for setting specific bits without knowledge of existing flags. |
| $setFlags| `UInt32,integer,null` | Indicates which flags to set. For details about the flags, please refer to the Accounts section of the Stellar documentation. The bit mask integer adds onto the existing flags of the account. This allows for setting specific bits without knowledge of existing flags. |
| $masterWeight| `UInt32,integer,null` | A number from 0-255 (inclusive) representing the weight of the master key. If the weight of the master key is updated to 0, it is effectively disabled. |
| $lowThreshold| `UInt32,integer,null` | A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a low threshold. |
| $mediumThreshold| `UInt32,integer,null` | A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a medium threshold. |
| $highThreshold| `UInt32,integer,null` | A number from 0-255 (inclusive) representing the threshold this account sets on all operations it performs that have a high threshold. |
| $homeDomain| `String32,string,null` | Sets the home domain of an account. See the Federation section of the Stellar documentation. |
| $signer| `Signer,null` | Add, update, or remove a signer from an account. Signer weight is a number from 0-255 (inclusive). The signer is deleted if the weight is 0. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-options](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-options)

## changeTrust()

Creates, updates, or deletes a trustline.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $asset| `ChangeTrustAsset,LiquidityPoolParameters,Asset,string` | The asset of the trustline. For example, if a user extends a trustline of up to 200 USD to an anchor, the line is USD:anchor. |
| $limit| `Int64,ScaledAmount,integer,string,null` | The limit of the trustline. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

## allowTrust()

Updates the authorized flag of an existing trustline.

This operation is deprecated as of Protocol 17- prefer SetTrustlineFlags instead.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $trustor| `AccountId,Addressable,string` | The account of the recipient of the trustline. |
| $assetCode| `AssetCode,string` | The 4 or 12 character-maximum asset code of the trustline the source account is authorizing. For example, if an issuing account wants to allow another account to hold its USD credit, the type is USD. |
| $authorize| `UInt32,integer` | Flag indicating whether the trustline is authorized. 1 if the account is authorized to transact with the asset. 2 if the account is authorized to maintain offers, but not to perform other transactions |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#allow-trust](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#allow-trust)

## accountMerge()

Transfers the XLM balance of an account to another account and removes
the source account from the ledger.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $destination| `Addressable,string` | The account that receives the remaining XLM balance of the source account. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#account-merge](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#account-merge)

## manageData()

Sets, modifies, or deletes a data entry (name/value pair) that is attached to an account.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $name| `String64,string` | String up to 64 bytes long. If this is a new Name it will add the given name/value pair to the account. If this Name is already present then the associated value will be modified. |
| $value| `DataValue,string,null` | Optional. If not present then the existing Name will be deleted. If present then this value will be set in the DataEntry. Up to 64 bytes long. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-data](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-data)

## bumpSequence()

Bumps forward the sequence number of the source account to the given sequence
number, invalidating any transaction with a smaller sequence number.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $bumpTo| `SequenceNumber,Int64,integer` | The desired value for the operation's source account sequence number. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

## createClaimableBalance()

Moves an amount of asset from the operation source account into a new ClaimableBalanceEntry.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $asset| `Asset,string` | Asset that will be held in the ClaimableBalanceEntry in the form asset_code:issuing_address or native (XLM). |
| $amount| `Int64,ScaledAmount,integer,string` | Amount of asset stored in the ClaimableBalanceEntry. |
| $claimants| `ClaimantList,Claimant,array<Claimant,string>` | List of Claimants (account address and ClaimPredicate pair) that can claim this ClaimableBalanceEntry. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-claimable-balance](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-claimable-balance)

## claimClaimableBalance()

Claims a ClaimableBalanceEntry that corresponds to the BalanceID and adds
the amount of an asset on the entry to the source account.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $balanceId| `ClaimableBalanceId,Hash,string` | BalanceID on the ClaimableBalanceEntry that the source account is claiming. The balanceID can be retrieved from a successful CreateClaimableBalanceResult. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#claim-claimable-balance](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#claim-claimable-balance)

## beginSponsoringFutureReserves()

Allows an account to pay the base reserves for another account; sponsoring
account establishes the is-sponsoring-future-reserves relationship. There
must also be an end sponsoring future reserves operation in the same transaction

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sponsoredId| `Addressable,string` | Account that will have its reserves sponsored. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#begin-sponsoring-future-reserves](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#begin-sponsoring-future-reserves)

## endSponsoringFutureReserves()

Terminates the current is-sponsoring-future-reserves relationship in
which the source account is sponsored.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $source| `Addressable,string` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#end-sponsoring-future-reserves](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#end-sponsoring-future-reserves)

## revokeAccountSponsorship()

Revoke an account sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $address| `Addressable,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeAccountSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeAccountSponsorship)

## revokeTrustLineSponsorship()

Revoke a trust line sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $address| `Addressable,string` |
| $asset| `Asset,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeTrustlineSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeTrustlineSponsorship)

## revokeOfferSponsorship()

Revoke an offer sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $sellerId| `Addressable,string` |
| $offerId| `Int64,integer` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeOfferSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeOfferSponsorship)

## revokeDataSponsorship()

Revoke a data sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $address| `Addressable,string` |
| $dataName| `String64,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeDataSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeDataSponsorship)

## revokeClaimableBalanceSponsorship()

Revoke a claimable balance sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $balanceId| `ClaimableBalanceId,Hash,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeClaimableBalanceSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeClaimableBalanceSponsorship)

## revokeLiquidityPoolSponsorship()

Revoke a liquidity pool sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $poolId| `PoolId,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship)

## revokeSignerSponsorship()

Revoke a signer sponsorship.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $address| `Addressable,string` |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship)
- [https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship](https://stellar.github.io/js-stellar-base/Operation.html#.revokeLiquidityPoolSponsorship)

## clawback()

Burns an amount in a specific asset from a receiving account.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $from| `Addressable,string` | Account address that receives the clawback. |
| $asset| `Asset,string` | Asset held by the destination account. |
| $amount| `Int64,ScaledAmount,integer,string` | Amount of the aforementioned asset to burn. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#clawback](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#clawback)

## clawbackClaimableBalance()

Claws back an unclaimed ClaimableBalanceEntry, burning the pending
amount of the asset.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $balanceId| `ClaimableBalanceId,Hash,string` | The BalanceID on the ClaimableBalanceEntry that the source account is claiming, which can be retrieved from a successful CreateClaimableBalanceResult |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

## setTrustLineFlags()

Allows issuing account to configure authorization and trustline flags to an asset.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $trustor| `AccountId,Addressable,string` | The account that established this trustline. |
| $asset| `Asset,string` | The asset trustline whose flags are being modified. |
| $authorized| `boolean,null` | Optional. Toggle the 'authorized' flag. |
| $authorizedToMaintainLiabilities| `boolean,null` | Optional. Toggle the 'authorizedToMaintainLiabilities' flag. |
| $clawbackEnabled| `boolean,null` | Optional. Toggle the 'clawbackEnabled' flag. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-trustline-flags](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-trustline-flags)

## liquidityPoolDeposit()

Deposits assets into a liquidity pool, increasing the reserves of a
liquidity pool in exchange for pool shares.

Parameters to this operation depend on the ordering of assets in the
liquidity pool: “A” refers to the first asset in the liquidity pool,
and “B” refers to the second asset in the liquidity pool.

If the pool is empty, then this operation deposits maxAmountA of A and
maxAmountB of B into the pool. If the pool is not empty, then this
operation deposits at most maxAmountA of A and maxAmountB of B
into the pool. The actual amounts deposited are determined
using the current reserves of the pool. You can use these
parameters to control a percentage of slippage.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $poolId| `PoolId,string` | The PoolID for the Liquidity Pool to deposit into. |
| $maxAmountA| `Int64,ScaledAmount,int,string` | Maximum amount of first asset to deposit. |
| $maxAmountB| `Int64,ScaledAmount,int,string` | Maximum amount of second asset to deposit. |
| $minPrice| `Price,string` | Minimum depositA/depositB. |
| $maxPrice| `Price,string` | Maximum depositA/depositB. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-deposit](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-deposit)

## liquidityPoolWithdraw()

Withdraw assets from a liquidity pool, reducing the number of pool
shares in exchange for reserves of a liquidity pool.

The minAmountA and minAmountB parameters can be used to control
a percentage of slippage from the "spot price" on the pool.

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $poolId| `PoolId,string` | The PoolID for the Liquidity Pool to withdraw from. |
| $amount| `Int64,ScaledAmount,int,string` | Amount of pool shares to withdraw. |
| $minAmountA| `Int64,ScaledAmount,int,string` | Minimum amount of the first asset to withdraw. |
| $minAmountB| `Int64,ScaledAmount,int,string` | Minimum amount of the second asset to withdraw. |
| $source| `Addressable,string,null` | Optional. Defaults to the transaction's source account. |

### Return Type

`Operation`

### Further Reading:

- [https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-withdraw](https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-withdraw)

###### This page was dynamically generated from the OperationService source code.

