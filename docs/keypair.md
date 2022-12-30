# Keypairs 

## generate()

Return a keypair from random bytes.

### Return Type

`Keypair`

## fromPrivateKey()

Return a keypair from a string of private key bytes.

### Parameters

| Name | Type |
| ---- | ---- |
| $bytes| `string` |

### Return Type

`Keypair`

## fromPublicKey()

Return a keypair from a string of public key bytes.

### Parameters

| Name | Type |
| ---- | ---- |
| $bytes| `string` |

### Return Type

`Keypair`

## fromSeed()

Return a keypair from a seed.

### Parameters

| Name | Type |
| ---- | ---- |
| $seed| `string` |

### Return Type

`Keypair`

## fromAddress()

Return a keypair from an address.

### Parameters

| Name | Type |
| ---- | ---- |
| $address| `string` |

### Return Type

`Keypair`

## canSign()

Determine whether or not a keypair is capable of signing.

### Parameters

| Name | Type |
| ---- | ---- |
| $keypair| `Keypair` |

### Return Type

`bool`

## sign()

Sign a payload with a qualified signatory.

### Parameters

| Name | Type |
| ---- | ---- |
| $signer| `Signatory` |
| $message| `string` |

### Return Type

`Signature,null`

###### This page was dynamically generated from the KeypairService source code.

