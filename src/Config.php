<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom;

use StageRightLabs\Bloom\Horizon\CurlHttp;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\Url;

final class Config
{
    /**
     * Prevent the use of __set() and __unset().
     */
    use NoChanges;

    /**
     * Constants for the allowed key names.
     */
    protected const FAKE = 'fake';
    protected const DEBUG = 'debug';
    protected const NETWORK_PASSPHRASE = 'network_passphrase';
    protected const NETWORK_URL = 'network_url';
    protected const ALLOW_FRIENDBOT = 'allow_friendbot';
    protected const FRIENDBOT_URL = 'friendbot_url';
    protected const LOGGING_PATH = 'logging_path';

    /**
     * The current configuration state.
     *
     * @var array<string, string|int|bool|null>
     */
    protected array $state = [
        // Disable outgoing requests for testing purposes.
        self::FAKE               => false,
        // Enable debug mode
        self::DEBUG              => false,
        // The network passphrase to use when hashing signature payloads.
        self::NETWORK_PASSPHRASE => Bloom::TEST_NETWORK_PASSPHRASE,
        // The URL for the desired Horizon instance.
        self::NETWORK_URL        => Bloom::TEST_NETWORK_URL,
        // Allow access to friendbot for funding accounts
        self::ALLOW_FRIENDBOT    => false,
        // The URL to use for friendbot funding requests
        self::FRIENDBOT_URL      => Bloom::FRIENDBOT_URL,
        // The location to write log files in debug mode
        self::LOGGING_PATH       => CurlHttp::DEFAULT_LOGGING_PATH,
    ];

    /**
     * Instantiate a new configuration instance.
     *
     * @param array<string, string|int|bool|null> $state
     */
    public function __construct(array $state = [])
    {
        $this->state = array_merge($this->state, $state);

        // If we are on the test network we should allow the usage of friendbot.
        if ($this->state[self::NETWORK_PASSPHRASE] == Bloom::TEST_NETWORK_PASSPHRASE) {
            $this->state[self::ALLOW_FRIENDBOT] = true;
        }
    }

    /**
     * Instantiate a a new configuration method from a static method.
     *
     * @param array<string, string|int|bool|null> $state
     * @return static
     */
    public static function make(array $state): static
    {
        return new static($state);
    }

    /**
     * Determine if fake mode is enabled.
     *
     * @return bool
     */
    public function isFake(): bool
    {
        return $this->state[self::FAKE] === true;
    }

    /**
     * Enable the 'fake' configuration value.
     *
     * @return self
     */
    public function withFakeEnabled(): self
    {
        /** @var static */
        $config = Copy::deep($this);
        $config->state[self::FAKE] = true;

        return $config;
    }

    /**
     * Disable the 'fake' configuration value.
     *
     * @return self
     */
    public function withFakeDisabled(): self
    {
        /** @var static */
        $config = Copy::deep($this);
        $config->state[self::FAKE] = false;

        return $config;
    }

    /**
     * Determine if debug mode is enabled.
     *
     * @return bool
     */
    public function debugIsEnabled(): bool
    {
        return $this->state[self::DEBUG] === true;
    }

    /**
     * Enable debug mode.
     *
     * @return self
     */
    public function withDebugEnabled(): self
    {
        /** @var static */
        $config = Copy::deep($this);
        $config->state[self::DEBUG] = true;

        return $config;
    }

    /**
     * Disable debug mode.
     *
     * @return self
     */
    public function withDebugDisabled(): self
    {
        /** @var static */
        $config = Copy::deep($this);
        $config->state[self::DEBUG] = false;

        return $config;
    }

    /**
     * Return the network passphrase.
     *
     * @return string
     */
    public function getNetworkPassphrase(): string
    {
        return strval($this->state[self::NETWORK_PASSPHRASE]);
    }

    /**
     * Accept a network passphrase value.
     *
     * @param string $passphrase
     * @return static
     */
    public function withNetworkPassphrase(string $passphrase): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::NETWORK_PASSPHRASE] = $passphrase;

        return $clone;
    }

    /**
     * Return the network URL. You can optionally provide a path and query
     * string values to be appended to the URL.
     *
     * @param string $path
     * @param array<string, mixed> $query
     * @return string
     */
    public function getNetworkUrl(string $path = '/', array $query = []): string
    {
        return Url::build(
            strval($this->state[self::NETWORK_URL]),
            $path,
            array_filter($query)
        );
    }

    /**
     * Accept a network url value.
     *
     * @param string $url
     * @return self
     */
    public function withNetworkUrl(string $url): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::NETWORK_URL] = $url;

        return $clone;
    }

    /**
     * Is the usage of friendbot allowed?
     *
     * @return bool
     */
    public function friendbotIsAllowed(): bool
    {
        return $this->state[self::ALLOW_FRIENDBOT] === true;
    }

    /**
     * Enable the usage of friendbot.
     *
     * @return self
     */
    public function withFriendbotEnabled(): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::ALLOW_FRIENDBOT] = true;

        return $clone;
    }

    /**
     * Disable the usage of friendbot.
     *
     * @return self
     */
    public function withFriendbotDisabled(): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::ALLOW_FRIENDBOT] = false;

        return $clone;
    }

    /**
     * Return the Friendbot URL. You can optionally provide a path and query
     * string values to be appended to the URL.
     *
     * @param string $path
     * @param array<string, mixed> $query
     * @return string
     */
    public function getFriendbotUrl(string $path = '/', array $query = []): string
    {
        return Url::build(strval($this->state[self::FRIENDBOT_URL]), $path, $query);
    }

    /**
     * Accept a Friendbot URL value.
     *
     * @param string $url
     * @return self
     */
    public function withFriendbotUrl(string $url): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::FRIENDBOT_URL] = $url;

        return $clone;
    }

    /**
     * Get the location to write log files in debug mode.
     *
     * @return string
     */
    public function getLoggingPath(): string
    {
        return strval($this->state[self::LOGGING_PATH]);
    }

    /**
     * Accept a logging path.
     *
     * @param string $path
     * @return self
     */
    public function withLoggingPath(string $path): self
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->state[self::LOGGING_PATH] = $path;

        return $clone;
    }

    /**
     * Return the state array.
     *
     * @return array<string, string|int|bool|null>
     */
    public function toArray(): array
    {
        return $this->state;
    }

    /**
     * Return a string representation of the state.
     *
     * @return string
     */
    public function __toString(): string
    {
        return strval(json_encode($this->toArray()));
    }

    /**
     * Return a string representation of the state.
     *
     * @return string
     */
    public function toJson(): string
    {
        return strval($this);
    }
}
