<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

interface Http
{
    /**
     * Perform a GET request.
     *
     * @param string $url
     * @param array<string, mixed> $options
     * @return Response
     */
    public function get(string $url, array $options = []): Response;

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array<string, string> $payload
     * @param array<string, mixed> $options
     * @return Response
     */
    public function post(string $url, array $payload = [], array $options = []): Response;
}
