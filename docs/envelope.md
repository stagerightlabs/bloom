# Envelopes 

## enclose()

Wrap a transaction in an envelope to prepare for signing.

### Parameters

| Name | Type |
| ---- | ---- |
| $transaction| `Transaction,TransactionV0,FeeBumpTransaction` |

### Return Type

`TransactionEnvelope`

## sign()

Add a new signature to an envelope's signature list.

### Parameters

| Name | Type |
| ---- | ---- |
| $envelope| `TransactionEnvelope` |
| $signer| `Signatory` |

### Return Type

`TransactionEnvelope`

## post()

Submit a signed transaction to Horizon.

### Parameters

| Name | Type |
| ---- | ---- |
| $envelope| `TransactionEnvelope` |

### Return Type

`TransactionResource,Error`

## toXdr()

Convert a transaction envelope to a Base 64 XDR string.

### Parameters

| Name | Type |
| ---- | ---- |
| $envelop| `TransactionEnvelope` |

### Return Type

`string`

## fromXdr()

Decode a transaction envelope from a base 64 XDR string.

### Return Type

`TransactionEnvelope`

###### This page was dynamically generated from the EnvelopeService source code.

