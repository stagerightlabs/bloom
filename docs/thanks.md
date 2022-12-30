This project would not have been possible without the tireless effort of many developers who have contributed to the open source community. There are a handful of projects that I would like to acknowledge with special thanks:

### Stellar Quest

[Stellar Quest](https://quest.stellar.org/) was my original inspiration; it planted the seed for this project. kalepail has made it into a gem of an educational tool.

### zulucrypto/stellar-api

The [zulucrypto/stellar-api](https://github.com/zulucrypto/stellar-api) library was the original Stellar PHP integration. Zulucrypto forged a tremendous path and figured out a lot of nitty-gritty details that were extremely helpful to me in wrapping my head around some of the more complicated elements of this project; especially the cryptography concerns.

### stellar/js-stellar-base/ and stellar/js-stellar-sdk

The [js-stellar-base](https://github.com/stellar/js-stellar-base/) and [js-stellar-sdk](https://github.com/stellar/js-stellar-sdk) packages were instrumental in helping me understand the Stellar XDR modeling and how to interact with resources on the network.

### brick/math

[brick/math](https://github.com/brick/math) is an excellent package for handling arbitrary precision in PHP. Further, it also provided a lot of architectural inspiration; especially the static instantiation methods and the creation of proxies to wrap certain types of vendor classes.

### stripe/stripe-php

Around the time I first started thinking about creating Bloom the [Stripe PHP](https://github.com/stripe/stripe-php) package was moving towards a centralized singleton architecture; using a single top level class as the entry point for working with the entire library and using a service factory to delegate interactions to their designated services.  Bloom draws heavily upon this idea and borrows liberally from that service factory pattern.

### phpstan/phpstan

I had never used a static analysis tool before starting work on Bloom; it has been quite an eye-opening experience. [PHPtan](https://github.com/phpstan/phpstan) is a remarkable tool that has provided excellent guidance for handling type safety in my code and has changed how I think about certain aspects of writing software.

### sebastianbergmann/phpunit

[PHPUnit](https://github.com/sebastianbergmann/phpunit) is part of the bedrock of the PHP community. I often feel like Sebastian Bergmann and the other contributors don't get enough credit for the amazing tool they have created - legions of developers have been able to leverage testing and TDD in there code thanks to their tireless effort.  I don't think the PHP ecosystem would be where it is today without this foundational tool.

### laravel/framework

While not a direct influence for this project, the zen of the [Laravel](https://github.com/laravel/framework/) codebase is remarkable. Working with Laravel is a joy, and it has inspired me to pursue zen in my own code wherever possible.

### Finally

I have tremendous respect for the Stellar Development Foundation and their stewardship of the Stellar blockchain.  In the sea of tumult that is the blockchain community they have been a rock of equanimity; I am grateful for their calm and steady approach to the pursuit of their vision.

I would also like to thank my family for allowing me the time to work on this project in my spare time.
