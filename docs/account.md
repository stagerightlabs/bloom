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

`Account`

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

###### This page was dynamically generated from the AccountService source code.

