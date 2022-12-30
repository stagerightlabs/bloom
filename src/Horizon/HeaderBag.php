<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

final class HeaderBag
{
    /**
     * @var array<string, array<string>>
     */
    protected array $headers;

    /**
     * Instantiate a new class instance.
     *
     * @param array<string, array<string>>|string $raw
     */
    public function __construct(array|string $raw = '')
    {
        $this->headers = is_array($raw) ? $raw : $this->parseHeaderString($raw);
    }

    /**
     * Create a new instance from a raw header string.
     *
     * @param array<string, array<string>>|string $raw
     * @return static
     */
    public static function make(array|string $raw): static
    {
        return new static($raw);
    }

    /**
     * Does this header bag contain a value for the given header?
     *
     * @param string $key
     * @return bool
     */
    public function hasHeader(string $key): bool
    {
        return array_key_exists(strtolower($key), $this->headers) && !empty($this->headers[$key]);
    }

    /**
     * Return the contents of the requested header.
     *
     * @param string $key
     * @return array<string>
     */
    public function getHeader(string $key = ''): array
    {
        if ($this->hasHeader($key)) {
            return $this->headers[$key];
        }

        return [];
    }

    /**
     * Return all of the headers as an array.
     *
     * @return array<string, array<string>>
     */
    public function getAll(): array
    {
        return $this->headers;
    }

    /**
     * Parse a header string into an array of headers. Values will always be an array.
     *
     * @param string $string
     * @return array<string, array<string>>
     */
    private function parseHeaderString($string): array
    {
        $headers = [];

        foreach (explode("\r\n", $string) as $line) {
            // Ignore empty lines and lines without colons
            if (empty($line) || !strpos($line, ':')) {
                continue;
            }

            // Extract the key and value
            [$key, $value] = explode(': ', $line);

            // Add the key => value to the headers array
            $headers[strtolower(trim($key))][] = trim($value);
        }

        return $headers;
    }
}
