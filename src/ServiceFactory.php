<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom;

/**
 * Locate and instantiate service classes as needed. Borrowed heavily from
 * the Stripe PHP client service factory architecture.
 *
 * @see https://github.com/stripe/stripe-php/blob/6bfd895eaa3f6ebb49c10f9ab9ccc9e5baadded5/lib/Service/AbstractServiceFactory.php
 */
final class ServiceFactory
{
    /**
     * The service class registry.
     *
     * @var array<string, string>
     */
    protected static $registry = [
        'account'          => \StageRightLabs\Bloom\Account\AccountService::class,
        'asset'            => \StageRightLabs\Bloom\Asset\AssetService::class,
        'claimableBalance' => \StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceService::class,
        'envelope'         => \StageRightLabs\Bloom\Envelope\EnvelopeService::class,
        'friendbot'        => \StageRightLabs\Bloom\Friendbot\FriendbotService::class,
        'horizon'          => \StageRightLabs\Bloom\Horizon\HorizonService::class,
        'keypair'          => \StageRightLabs\Bloom\Keypair\KeypairService::class,
        'liquidityPool'    => \StageRightLabs\Bloom\LiquidityPool\LiquidityPoolService::class,
        'operation'        => \StageRightLabs\Bloom\Operation\OperationService::class,
        'predicate'        => \StageRightLabs\Bloom\ClaimableBalance\ClaimPredicateService::class,
        'transaction'      => \StageRightLabs\Bloom\Transaction\TransactionService::class,
    ];

    /**
     * The collection of previously instantiated services.
     *
     * @var array<string, Service>
     */
    protected array $services = [];

    /**
     * The originating client class.
     *
     * @var Bloom
     */
    protected Bloom $bloom;

    /**
     * Instantiate a new ServiceFactory instance.
     *
     * @param Bloom $bloom
     */
    public function __construct(Bloom $bloom)
    {
        $this->bloom = $bloom;
    }

    /**
     * Defer a call to the named service.
     *
     * @param string $name
     * @return null|Service
     * @see https://github.com/stripe/stripe-php/blob/3a029598395bb4c7cfafa64707a553f4b01a9a12/lib/Service/AbstractServiceFactory.php#L44
     */
    public function __get(string $name): ?Service
    {
        if (array_key_exists($name, $this->services)) {
            return $this->services[$name];
        }

        if ($service = $this->makeService($name)) {
            $this->services[$name] = $service;
            return $this->services[$name];
        }

        \trigger_error('Undefined property: ' . static::class . '::$' . $name);

        return null;
    }

    /**
     * Instantiate a service class.
     *
     * @param string $name
     * @return Service|null
     */
    public function makeService(string $name): ?Service
    {
        $class = array_key_exists($name, self::$registry)
            ? self::$registry[$name]
            : null;

        if (!$class || !class_exists($class)) {
            return null;
        }

        /* @phpstan-ignore-next-line */
        return new $class($this->bloom);
    }

    /**
     * Get the list of registered service classes.
     *
     * @return array<string, string>
     */
    public static function getRegistry(): array
    {
        return self::$registry;
    }
}
