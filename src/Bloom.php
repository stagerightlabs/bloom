<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom;

/**
 * Bloom
 *
 * A PHP SDK for the Stellar Horizon API.
 *
 * Created and maintained by Ryan Durham and Stage Right Labs, LLC
 *
 * @link https://stellar.org
 * @link https://developers.stellar.org/docs
 * @link https://developers.stellar.org/api
 * @property \StageRightLabs\Bloom\Account\AccountService $account
 * @property \StageRightLabs\Bloom\Asset\AssetService $asset
 * @property \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceService $claimableBalance
 * @property \StageRightLabs\Bloom\Envelope\EnvelopeService $envelope
 * @property \StageRightLabs\Bloom\Friendbot\FriendbotService $friendbot
 * @property \StageRightLabs\Bloom\Horizon\HorizonService $horizon
 * @property \StageRightLabs\Bloom\Keypair\KeypairService $keypair
 * @property \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolService $liquidityPool
 * @property \StageRightLabs\Bloom\Operation\OperationService $operation
 * @property \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateService $predicate
 * @property \StageRightLabs\Bloom\Transaction\TransactionService $transaction
 */
final class Bloom
{
    // The currently compatible protocol version
    public const PROTOCOL_VERSION = 19;

    // Network URLs
    public const PUBLIC_NETWORK_URL = 'https://horizon.stellar.org/';
    public const TEST_NETWORK_URL = 'https://horizon-testnet.stellar.org/';
    public const FRIENDBOT_URL = 'https://friendbot.stellar.org/';

    // Network Passphrases
    public const PUBLIC_NETWORK_PASSPHRASE = 'Public Global Stellar Network ; September 2015';
    public const TEST_NETWORK_PASSPHRASE = 'Test SDF Network ; September 2015';

    // The maximum number of operations allowed in a transaction
    public const MAX_OPS_PER_TX = 100;

    // The minimum fee charged per operation, in stroops
    public const MINIMUM_OPERATION_FEE = 100;

    // The maximum number of signers allowed for each transaction
    public const MAX_SIGNERS = 20;

    // The default liquidity pool fee. 30 basis points (0.3%)
    public const LIQUIDITY_POOL_FEE_V18 = 30;

    // The asset code used for the native XLM asset
    public const NATIVE_ASSET_CODE = 'XLM';

    /**
     * @var Config
     */
    public Config $config;

    /**
     * @var ServiceFactory
     */
    private ?ServiceFactory $serviceFactory = null;

    /**
     * Build a new client instance.
     *
     * The configuration array structure is described in Config.php.
     *
     * @param Config|array<string, string|int|bool|null> $config
     * @return self
     */
    public function __construct(Config|array $config = [])
    {
        $this->config = is_array($config)
            ? Config::make($config)
            : $config;
    }

    /**
     * Build a new client instance from a static method.
     *
     * @param Config|array<string, string|int|bool|null> $config
     * @return self
     */
    public static function make(Config|array $config = []): self
    {
        return new static($config);
    }

    /**
     * Build a new fake client instance from a static method.
     *
     * @param Config|array<string, string|int|bool|null> $config
     * @return static
     */
    public static function fake(Config|array $config = []): static
    {
        $config = is_array($config)
            ? Config::make($config)
            : $config;

        return new static($config->withFakeEnabled());
    }

    /**
     * Is the Bloom client operating in 'fake' mode?
     *
     * @return bool
     */
    public function isFake(): bool
    {
        return $this->config->isFake();
    }

    /**
     * Defer service calls to their providers.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name): mixed
    {
        if (is_null($this->serviceFactory)) {
            $this->serviceFactory = new ServiceFactory($this);
        }

        return $this->serviceFactory->__get($name);
    }
}
