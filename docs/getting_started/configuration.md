# Configuration Options

## Setting Config Values
There are certain aspects of the Bloom client library that can be adjusted via configuration. To do so, instantiate the client with an associative array of configuration values:

```php
$bloom = new Bloom([
    'network_url' => 'http://some/horizon/endpoint',
]);
``` 

Any values not included in that array will be set to their default values.

Note: The bloom object is immutable.To change the configuration you will need to create a new instance.

## Options

The following configuration values are available:

- `fake` (boolean): With fake mode enabled the client will not make any real HTTP requests. This is useful for testing. Default: `false`.
- `debug` (boolean): With debug mode enabled a log file will be created for every HTTP request that contains details of the request and response. Default: `false`.
- `network_passphrase` (string): The network passphrase to use when signing transactions. This will be different for each network you want to use; mainnet, testnet or some custom network. Default: the testnet passphrase.
- `network_url` (string): The URL of the Horizon endpoint to use when making HTTP requests. This will be different for each network you want to use; mainnet, testnet or some custom network. Default: the testnet Horizon URL.
- `allow_friendbot` (boolean): Whether or not to allow FriendBot requests for creating test accounts. Default: `true`. It will automatically be set to false when connecting to mainnet.
- `friendbot_url` (string): The URL to use when making FriendBot requests, mostly useful for connecting to custom networks. Default: the testnet FriendBot URL.
- `logging_path` (string): The location on disk to write log files when using debug mode. Default: the 'log' directory for this library in your vendor folder.

## Stellar Network Constants

More often than not you will be using Bloom to connect to one of the two primary networks operated by the SDF: mainnet or testnet. To make that easier there are some constants you can use when configuring your Bloom instance:

To connect to mainnet:

```php
$bloom = new Bloom([
    'network_url' => Bloom::PUBLIC_NETWORK_URL,
    'network_passphrase' => Bloom::PUBLIC_NETWORK_PASSPHRASE,
]);
``` 

To connect to testnet:

```php
$bloom = new Bloom([
    'network_url' => Bloom::TEST_NETWORK_URL,
    'network_passphrase' => Bloom::TEST_NETWORK_PASSPHRASE,
]);
``` 

However, Bloom will connect to testnet by default, so you do not need to specify those configuration options when using testnet:

```php
$bloom = new Bloom();
```


