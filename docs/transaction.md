# Transactions 

## create()

Create a new transaction

### Parameters

| Name | Type |
| ---- | ---- |
| $sourceAddress| `Addressable,string` |
| $sequenceNumber| `Int64,string,int` |
| $fee| `UInt32,int` |

### Return Type

`Transaction`

## createFeeBumpTransaction()

Create a new fee bump transaction.

### Parameters

| Name | Type |
| ---- | ---- |
| $envelope| `TransactionEnvelope` |
| $fee| `Int64,int` |
| $feeSource| `AccountId,Addressable,string` |

### Return Type

`FeeBumpTransaction`

### Further Reading:

- [https://developers.stellar.org/docs/glossary/fee-bumps/#replace-by-fee](https://developers.stellar.org/docs/glossary/fee-bumps/#replace-by-fee)

## addOperation()

Add an operation to a transaction.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $operation| `Operation` |

### Return Type

`Transaction`

## addMinimumTimePrecondition()

Add a minimum time precondition to a transaction.

An integer value will be interpreted as a Unix epoch.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $minTime| `TimePoint,DateTime,string,int` |

### Return Type

`Transaction`

## addMaximumTimePrecondition()

Add a maximum time precondition to a transaction.

An integer value will be interpreted as a Unix epoch.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $maxTime| `TimePoint,DateTime,string,int` |

### Return Type

`Transaction`

## setTimeout()

Set the transaction to expire after a specified number of seconds.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $seconds| `int` |

### Return Type

`Transaction`

## addMinimumLedgerOffsetPrecondition()

Add a minimum ledger offset as a precondition. The transaction will
only be valid after this many ledgers have closed.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $minLedgerOffset| `UInt32,int` |

### Return Type

`Transaction`

## addMaximumLedgerOffsetPrecondition()

Add a maximum ledger offset as a precondition. The transaction will
only be valid until this many ledgers have closed. If set to
zero only the minimum ledger offset will be considered.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $maxLedgerOffset| `UInt32,int` |

### Return Type

`Transaction`

## addMinimumSequenceNumberPrecondition()

Add a minimum source account sequence number precondition. Defines
what sequence number the source account must reach before a
transaction becomes valid.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $minimumSequenceNumber| `SequenceNumber,OptionalSequenceNumber` |

### Return Type

`Transaction`

## addMinimumSequenceAgePrecondition()

Add a minium sequence age precondition. This defines the how much older
the current ledger's sequence time must be than the source account's
sequence time for a transaction to be valid.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $minimumSequenceAge| `Duration` |

### Return Type

`Transaction`

## addMinimumSequenceLedgerGapPrecondition()

Add a minimum sequence ledger gap precondition. This defines how much
greater the current ledger number must be than the source account's
sequence ledger number for a transaction to be valid.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |
| $minimumSequenceLedgerGap| `UInt32,int` |

### Return Type

`Transaction`

## removePreconditions()

Remove existing preconditions from a transaction.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction` |

### Return Type

`Transaction`

###### This page was dynamically generated from the TransactionService source code.

