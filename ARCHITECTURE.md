# Architecture

This documents outlines the high-level architecture of the Bloom SDK for Stellar Horizon. More information can be found in the [documentation folder](docs).

In a very broad sense the code in this project is organized into three different layers that build on top of one another; each one oriented towards a different goal. This arrangement allows for easy communication with Horizon without also needing to make any concessions in the API presented to developers using this library to build tools. The directory structure is arranged by resource type rather than layer, though. Each folder contains classes that make up parts all three layers.

Here is a brief rundown of each architectural tier:

## Horizon Data Structures

The innermost layer is comprised of the data structures that interact directly with Horizon. These are defined in the [Stellar .x](https://github.com/stellar/stellar-xdr) files maintained by the [SDF](https://stellar.org/) and make up the content of the data sent over the wire to and from Horizon. The classes that represent those data structures typically implement interfaces provided by the [PHPXDR](https://github.com/stagerightlabs/phpxdr) library, and can be converted to and from [XDR](https://developers.stellar.org/docs/encyclopedia/xdr). Most other Stellar SDK packages use code generators to convert the Stellar.x data structures into language constructs, however this one does not. That may change in the future but for the time being the translation into PHP classes has been done by hand; largely to help facilitate integration with the other architectural layers outlined in this document.

## Developer facing API

The outermost layer is the API that is presented to developers using this library in their projects. This layer consists mostly of service classes that are wired into a central `Bloom()` class in a "hub and spoke" fashion. The `Bloom()` class manages configuration details and can be instantiated as a singleton. The service classes are accessed as properties on that singleton and receive any configuration details they need from that central class. This model was heavily inspired by the [Stripe PHP library](https://github.com/stripe/stripe-php). The goal of this construction is to have everything that a developer needs to work with Horizon be accessible through that singleton class.

Additionally, the service classes themselves draw influence from functional programming idioms. Almost all of the objects managed by this library are immutable by default. Rather than interacting with those objects directly the service classes are used to operate on those objects and return modified copies, much like you might see in a functional programming language. For example:

```php
// Instead of this object oriented approach:
$account->incrementSequenceNumber();

// We use this functional approach:
$bloom = new Bloom();
$account = $bloom->account->incrementSequenceNumber($account);
```

## Interstitial Layer

The third layer connects the inner and outer layers mentioned above. It is a translation mechanism that connects the developer facing API and the Horizon facing API, allowing them to communicate with each other. This interstitial API mostly consists of helper methods added to the XDR data structures to simplify the manipulation of those data structures and abstract away their complexities where possible. Typically speaking the high level service classes call on these utility methods to prepare XDR data for communication with Horizon and interpreting the XDR received in response.
