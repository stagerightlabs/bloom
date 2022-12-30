<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class FakeHttp implements Http
{
    /**
     * @var array<Response>
     */
    protected array $mockedResponses;

    /**
     * Instantiate a new class instance.
     *
     * @param Response|array<Response> $mockedResponses
     */
    public function __construct($mockedResponses = [])
    {
        if (!is_array($mockedResponses)) {
            $mockedResponses = [$mockedResponses];
        }

        $this->mockedResponses = $mockedResponses;
    }

    /**
     * Perform a simulated GET request.
     *
     * @param string $url
     * @param array<string, mixed> $options
     * @return Response
     */
    public function get(string $url, array $options = []): Response
    {
        if (empty($this->mockedResponses)) {
            throw new \Exception('No mocked responses have been registered.');
        }

        return array_shift($this->mockedResponses);
    }

    /**
     * Perform a simulated POST request.
     *
     * @param string $url
     * @param array<string, string> $payload
     * @param array<string, mixed> $options
     * @return Response
     */
    public function post(string $url, array $payload = [], array $options = []): Response
    {
        if (empty($this->mockedResponses)) {
            throw new \Exception('No mocked responses have been registered.');
        }

        return array_shift($this->mockedResponses);
    }
}
