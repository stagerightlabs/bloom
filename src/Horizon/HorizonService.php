<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Service;
use StageRightLabs\Bloom\Utility\Copy;

final class HorizonService extends Service
{
    /**
     * @var array<Response>
     */
    private array $mockedResponses = [];

    /**
     * Perform a 'GET' request.
     *
     * @param string $url
     * @return Resource|Error
     */
    public function get(string $url): Resource|Error
    {
        $response = $this->getClient()->get($url);

        return $this->responseIsAnError($response)
            ? Error::fromResponse($response)
            : Resource::fromResponse($response);
    }

    /**
     * Perform a 'POST' request.
     *
     * @param string $url
     * @param array<string, string> $params
     * @return Resource|Error
     */
    public function post(string $url, array $params = []): Resource|Error
    {
        $response = $this->getClient()->post($url, $params);

        return $this->responseIsAnError($response)
            ? Error::fromResponse($response)
            : Resource::fromResponse($response);
    }

    /**
     * Build a Guzzle HTTP client
     *
     * @return Http
     */
    public function getClient(): Http
    {
        return $this->bloom->isFake()
            ? $this->createFakeHttpClient()
            : $this->createCurlHttpClient();
    }

    /**
     * Create a Guzzle handler stack for live requests.
     *
     * @return CurlHttp
     */
    private function createCurlHttpClient(): CurlHttp
    {
        return new CurlHttp(
            loggingEnabled: $this->bloom->config->debugIsEnabled(),
            loggingPath: $this->bloom->config->getLoggingPath()
        );
    }

    /**
     * Create a Guzzle handler stack for simulated requests.
     *
     * @return FakeHttp
     */
    private function createFakeHttpClient(): FakeHttp
    {
        return new FakeHttp($this->mockedResponses);
    }

    /**
     * Add responses to the mocked responses stack.
     *
     * @param Response|array<Response> $response
     * @return static
     */
    public function withResponse(Response|array $response): static
    {
        if (!is_array($response)) {
            $response = [$response];
        }

        /** @var static */
        $clone = Copy::deep($this);

        if ($this->bloom->config->isFake()) {
            foreach ($response as $hr) {
                $this->mockedResponses[] = $hr;
            }
        }

        return $clone;
    }

    /**
     * Are there any mock responses in the stack?
     *
     * @return bool
     */
    public function hasMockedResponses(): bool
    {
        return count($this->mockedResponses) > 0;
    }

    /**
     * Does a response represent an error report from Horizon?
     *
     * @param Response $response
     * @return bool
     */
    protected function responseIsAnError(Response $response): bool
    {
        return $response->getStatusCode() > 399;
    }
}
