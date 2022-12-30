# Predicates 

## unconditional()

Create an 'unconditional' claim predicate.

### Return Type

`ClaimPredicate`

## and()

Create an 'and' predicate that requires that all provided predicates pass.

### Parameters

| Name | Type |
| ---- | ---- |
| $claimPredicateList| `ClaimPredicateList,array<ClaimPredicate>` |

### Return Type

`ClaimPredicate`

## or()

Create an 'or' predicate that requires at least one of the provided predicates pass.

### Parameters

| Name | Type |
| ---- | ---- |
| $claimPredicateList| `ClaimPredicateList,array<ClaimPredicate>` |

### Return Type

`ClaimPredicate`

## not()

Create a 'not' predicate that inverts the provided predicate.

### Parameters

| Name | Type |
| ---- | ---- |
| $claimPredicate| `ClaimPredicate,OptionalClaimPredicate` |

### Return Type

`ClaimPredicate`

## beforeAbsoluteTime()

Create a new 'before absolute time' predicate that will be valid if
the ledger close time is less than the absolute time provided.

### Parameters

| Name | Type |
| ---- | ---- |
| $epoch| `DateTime,TimePoint,Int64,int` |

### Return Type

`ClaimPredicate`

## beforeRelativeTime()

Create a new 'before relative time' predicate that will be valid
for the specified number of  seconds after the close of the
ledger in which this claimable balance entry was created.

### Parameters

| Name | Type |
| ---- | ---- |
| $seconds| `Int64,int` |

### Return Type

`ClaimPredicate`

## collect()

Create a claim predicate list from an array of claim predicates.

### Parameters

| Name | Type |
| ---- | ---- |
| $predicates| `ClaimPredicate,array<ClaimPredicate>` |

### Return Type

`ClaimPredicateList`

###### This page was dynamically generated from the ClaimPredicateService source code.

