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
    public static function query(string $url): array
    {
        $query = parse_url($url, PHP_URL_QUERY);
        $query = $query ? $query : '';

        parse_str($query, $payload);

        return $payload;
    }

    /**
     * Return the base of a given URL: the scheme and the authority.
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
     * @param array<string, mixed> $params
     * @return string
     */
    public static function build(string $base, string $path = '/', array $params = []): string
    {
        $base = self::base($base);
        $path = str_starts_with($path, '/') ? $path : '/' . $path;
        $query = empty($params) ? '' : '?' . http_build_query($params);

        return $base . $path . $query;
    }
}
