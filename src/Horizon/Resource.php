<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Utility\Json;
use StageRightLabs\Bloom\Utility\NoConstructor;

class Resource
{
    /**
     * Properties
     */
    protected Json $payload;
    protected ?Response $response;

    /**
     * Ensure this class can never be instantiated without a payload.
     */
    final public function __construct()
    {
        $this->payload = new Json();
        $this->response = null;
    }

    /**
     * Instantiate a new resource instance.
     *
     * @param Json|array<int|string, mixed>|string $payload
     * @param Response|null $response
     * @return static
     */
    public static function wrap(Json|array|string $payload = '', Response $response = null): static
    {
        if (!$payload instanceof Json) {
            $payload = is_array($payload)
                ? Json::fromArray($payload)
                : Json::of($payload);
        }

        $resource = new static();
        $resource->payload = $payload;
        $resource->response = $response;

        return $resource;
    }

    /**
     * Create a resource from a response.
     *
     * @param Response $response
     * @return static
     */
    public static function fromResponse(Response $response): static
    {
        return static::wrap($response->getBody(), $response);
    }

    /**
     * Return the original server response.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Return the payload content as an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(): array
    {
        return $this->payload->getAll();
    }

    /**
     * Return the payload as an \StageRightLabs\Bloom\Utility\Json instance.
     *
     * @return Json
     */
    public function toJson(): Json
    {
        return $this->payload;
    }

    /**
     * A Horizon request will return an error or a resource/response. If given
     * a resource then the request was successful. This mirrors a similar
     * function on the Horizon/Error class that returns true.
     *
     * @return bool
     */
    public function requestFailed(): bool
    {
        return false;
    }

    /**
     * Return the links array.
     *
     * @return array<string, string>
     */
    public function getLinks(): array
    {
        $arr = $this->payload->getArray('_links') ?? [];

        return array_reduce(array_keys($arr), function ($carry, $key) use ($arr) {
            $carry[$key] = $arr[$key]['href'];
            return $carry;
        }, []);
    }

    /**
     * Return a single link from the links array.
     *
     * @param string $key
     * @return string|null
     */
    public function getLink(string $key): ?string
    {
        return $this->payload->getString("_links.{$key}.href");
    }
}
