# Accounts 

Tools for working with Stellar accounts. These methods can be accessed by the
`account` property on a bloom instance: `$bloom->account`:


## retrieve()

Fetch the details of a single account from Horizon.

Example:
```php
$bloom = new Bloom();
$account = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
```

It is possible to instantiate an Account object without the Horizon data.
The `hasBeenLoaded()` method will tell you if the data is available:

```php
if ($account->hasBeenLoaded()) {
    // do something...
}
```

### Parameters

| Name | Type |
| ---- | ---- |
| $addressable| `Addressable,string` |

### Return Type

`Account,Error`

### Further Reading:

- [https://developers.stellar.org/api/resources/accounts/single/](https://developers.stellar.org/api/resources/accounts/single/)

## incrementSequenceNumber()

Increment an account's sequence number. No changes will be made if the
current sequence number has not been loaded from Horizon.

Example
```php
$account = $bloom->account->incrementSequenceNumber($account);
$sequenceNumber = $account->getSequenceNumber();
$int = $sequenceNumber->toNativeInt();
$string = $sequenceNumber->toNativeString();
```

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $account| `Account` |
| $bump| `int` | Increment amount. The default is 1. |

### Return Type

`Account`

## transactions()

Retrieve a paginated listing of an Account's transactions.

Example
```php
$account = $bloom->account->retrieve('GBVG2QOHHFBVHAEGNF4XRUCAPAGWDROONM2LC4BK4ECCQ5RTQOO64VBW');
$transactions = $bloom->account->transactions(account: $account, limit: 20, order: 'desc')
foreach ($transactions as $transaction) {
    // do something with the transaction resource object.
}
```

### Parameters

| Name | Type | Notes |
| ---- | ---- | ---- |
| $account| `Account,Addressable,string` |
| $cursor| `string,null` | A number that points to a specific location in a collection of responses. |
| $order| `string` | The sort order for the transactions; either 'asc' or 'desc'. Defaults to ascending if no value is set. |
| $limit| `int` | The maximum number of records returned. Must be between 1 and 200; the default is 10. |
| $includeFailed| `bool` | When true, failed transactions will be included in the response. Default is false. |

### Return Type

`TransactionResourceCollection,Error`

### Further Reading:

- [https://developers.stellar.org/api/resources/accounts/transactions/](https://developers.stellar.org/api/resources/accounts/transactions/)

###### This page was dynamically generated from the AccountService source code.

