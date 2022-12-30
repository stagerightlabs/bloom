<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

final class Response
{
    /**
     * Properties
     */
    protected int $statusCode;
    protected HeaderBag $headers;
    protected string $body;
    protected float $totalTime;

    /**
     * Instantiate a new class instance.
     *
     * @param int $statusCode
     * @param string|null $body
     * @param HeaderBag|array<string, array<string>> $headers
     * @param float $totalTime
     */
    public function __construct(int $statusCode, HeaderBag|array $headers = [], string $body = null, float $totalTime = 0)
    {
        $this->statusCode = $statusCode;
        $this->headers = is_array($headers) ? HeaderBag::make($headers) : $headers;
        $this->body = $body ?? '';
        $this->totalTime = $totalTime;
    }

    /**
     * Return the status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Return the headers.
     *
     * @return HeaderBag
     */
    public function getHeaders(): HeaderBag
    {
        return $this->headers;
    }

    /**
     * Return the body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Return the timing of the request in seconds.
     *
     * @return float
     */
    public function getTotalTime(): float
    {
        return $this->totalTime;
    }

    /**
     * Build a fake response from a json stub.
     *
     * @param string $stub
     * @param array<string, string> $swap
     * @param int $statusCode
     * @param array<string, array<string>> $headers
     * @return static
     */
    public static function fake(string $stub, array $swap = [], int $statusCode = 200, $headers = []): static
    {
        $path = realpath(__DIR__ . '/../../tests/Horizon/stubs/' . $stub . '.json');

        if (!$path) {
            throw new \Exception("Invalid stub file: '{$stub}'");
        }

        $body = strval(file_get_contents($path));

        foreach ($swap as $find => $replace) {
            $body = str_replace('[' . $find . ']', $replace, $body);
        }

        return new static($statusCode, $headers, $body, 0);
    }
}
