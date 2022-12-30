![Bloom Stellar SDK](https://banners.beyondco.de/Bloom.png?theme=light&packageManager=composer+require&packageName=stagerightlabs%2Fbloom&pattern=graphPaper&style=style_1&description=An+unofficial+Stellar+Horizon+SDK&md=1&showWatermark=1&fontSize=100px&images=beaker)

Bloom is an unofficial PHP SDK library for the [Stellar](https://stellar.org/) [Horizon API](https://developers.stellar.org/docs/).

I was inspired to create this library after being introduced to [Stellar Quest](https://quest.stellar.org/); a remarkable platform for discovering and exploring the building blocks of the Stellar network. I wanted to see if I could solve the exercises in PHP rather than Javascript or Golang. After many months this library is the result of that effort.

There are three guiding principals that have steered the decision making for this project:

- Reduce the number of third party dependencies wherever possible.
- Make objects immutable by default wherever possible.
- Leverage the up and coming type system improvements in PHP 8.* and static analysis tools (like PHPStan) to pursue type safety as much as possible.

Preliminary documentation can be found in the [documentation](docs/) folder. [Example usage can be found here](https://github.com/stagerightlabs/bloom-examples/).

This project would not have been possible without the tireless effort of developers and companies who have donated their work to the open source community. I am extremely grateful for the building blocks they have put in place. See [here](docs/thanks.md) for a more specific list of acknowledgments.

Bloom is an independent project that is not affiliated with the [Stellar Development Foundation](https://www.stellar.org/foundation).

## Installation

You can install the package via composer:

```bash
composer require stagerightlabs/bloom
```

### Architecture

Please see [ARCHITECTURE](ARCHITECTURE.md) for a high level outline of the structure of this repository.

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The Apache License 2. Please see [License File](LICENSE.md) for more information.
