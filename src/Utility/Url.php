<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

class Url
{
    /**
     * Return the scheme portion of a given URL.
     *
     * @param string $url
     * @return string
     */
    public static function scheme(string $url): string
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);

        return $scheme ? $scheme . '://' : '';
    }

    /**
     * Return the authority portion of a given URL; the domain name and the
     * port, if present.
     *
     * @param string $url
     * @return string
     */
    public static function authority(string $url): string
    {
        // Extract URL info
        $domain = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);

        // Ensure domain is always a string
        $domain = $domain ? $domain : '';

        return $port
            ? $domain . ':' . $port
            : $domain;
    }

    /**
     * Return the path portion of a given URL prepended by a forward slash.
     *
     * @param string $url
     * @return string
     */
    public static function path(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);

        return $path ? $path : '/';
    }

    /**
     * Return the query string of a given URL as an associative array.
     *
     * @param string $url
     * @return array<int|string, array<int, string>|string>
     */
    public static function parameters(string $url): array
    {
        $query = strval(parse_url($url, PHP_URL_QUERY));
        parse_str($query, $payload);

        return $payload;
    }

    /**
     * Extract a query string parameter from a URL.
     *
     * @param string $url
     * @param string $key
     * @return string|null
     */
    public static function parameter(string $url, string $key): ?string
    {
        $parameters = self::parameters($url);

        return array_key_exists($key, $parameters)
            ? strval($parameters[$key])
            : null;
    }

    /**
     * Return the base of a given URL; the scheme and the authority.
     *
     * @param string $url
     * @return string
     */
    public static function base(string $url): string
    {
        return self::scheme($url) . self::authority($url);
    }

    /**
     * Build a URL from a base, path and set of optional query string parameters.
     *
     * @param string $base
     * @param array<string, string|int|bool|null> $params
     * @return string
     */
    public static function build(string $base, string $path = '/', array $params = []): string
    {
        // Remove null values from the parameters array using strict evaluation
        $params = array_filter($params, fn ($e) => $e !== null);

        // Convert parameter values to strings
        $params = array_map(function ($value) {
            // True values will be converted to the string 'true'
            if ($value === true) {
                $value = 'true';
            }

            // False values will be converted to the string 'false'
            if ($value === false) {
                $value = 'false';
            }

            // Ensure everything else is converted to a string
            return strval($value);
        }, $params);

        // Prepare the URL components
        $base = self::base($base);
        $path = str_starts_with($path, '/') ? $path : '/' . $path;
        $query = empty($params) ? '' : '?' . http_build_query($params);

        // Return the constructed URL
        return $base . $path . $query;
    }
}
