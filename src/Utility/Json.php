<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

final class Json
{
    /**
     * @var array<int|string, mixed>
     */
    protected array $payload;

    /**
     * Create a new class instance.
     *
     * @param string $source
     */
    public function __construct(string $source = '')
    {
        $this->payload = json_decode(strval($source), $associative = true, $depth = 512, JSON_BIGINT_AS_STRING) ?? [];
    }

    /**
     * Create a new class instance statically.
     *
     * @param string $source
     * @return static
     */
    public static function of(string $source): static
    {
        return new static($source);
    }

    /**
     * Create a new instance from an array.
     *
     * @param array<int|string, mixed> $payload
     * @return static
     */
    public static function fromArray(array $payload): static
    {
        $json = new Json();
        $json->payload = $payload;

        return $json;
    }

    /**
     * Return the payload array keys.
     *
     * @return array<int|string>
     */
    public function getKeys(): array
    {
        return array_keys($this->payload);
    }

    /**
     * Does the payload have the specified key or keys?
     * Accepts 'dot' notation.
     *
     * @param string|array<string> $keys
     * @return bool
     */
    public function hasKey(string|array $keys): bool
    {
        foreach ((array)$keys as $key) {
            if (array_key_exists($key, $this->payload)) {
                continue;
            }

            $sub = $this->payload;
            foreach (explode('.', $key) as $segment) {
                // If the key points to an array we will zoom in on that sub-array.
                if (is_array($sub) && array_key_exists($segment, $sub) && is_array($sub[$segment])) {
                    $sub = $sub[$segment];
                    continue;
                }

                // If the final key does not exist in its sub-array, return false
                if (!array_key_exists($segment, $sub)) {
                    return false;
                };
            }
        }

        return true;
    }

    /**
     * Return the entire payload as an array.
     *
     * @return array<string|int, mixed>
     */
    public function getAll(): array
    {
        return (array)$this->payload;
    }

    /**
     * Return a value from the payload using 'dot' notation.
     *
     * @param string $key
     * @return mixed
     */
    private function getValue(string $key): mixed
    {
        $sub = $this->payload;
        $segments = explode('.', $key);

        foreach ($segments as $index => $segment) {
            if ($index + 1 == count($segments) && array_key_exists($segment, $sub)) {
                return $sub[$segment];
            }

            if (is_array($sub) && array_key_exists($segment, $sub)) {
                $sub = $sub[$segment];
            }
        }

        return null;
    }

    /**
     * Return a boolean value from the payload using nested 'dot' notation.
     *
     * @param string $key
     * @param bool $default
     * @return bool|null
     */
    public function getBoolean(string $key, bool $default = null): bool|null
    {
        $value = $this->getValue($key);

        return $value === null ? $default : boolval($value);
    }

    /**
     * Return an integer value from the payload using nested 'dot' notation.
     *
     * @param string $key
     * @param int $default
     * @return int|null
     */
    public function getInteger(string $key, int $default = null): int|null
    {
        $value = $this->getValue($key);

        return $value === null ? $default : intval($value);
    }

    /**
     * Return a string value from the payload using nested 'dot' notation.
     *
     * @param string $key
     * @param string $default
     * @return string|null
     */
    public function getString(string $key, string $default = null): string|null
    {
        $value = $this->getValue($key);

        return $value === null ? $default : strval($value);
    }

    /**
     * Return an array value from the payload using nested 'dot' notation.
     *
     * @param string $key
     * @param array<string|int, mixed> $default
     * @return array<string|int, mixed>|null
     */
    public function getArray(string $key, array $default = null): array|null
    {
        $value = $this->getValue($key);

        return $value === null ? $default : (array)$value;
    }

    /**
     * Allow an instance of this class to be converted to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        $encoded = json_encode($this->payload);

        return $encoded ? $encoded : '';
    }

    /**
     * Return the payload as a JSON string.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return strval($this);
    }
}
