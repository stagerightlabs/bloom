<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Json;

class Resource
{
    /**
     * Properties
     */
    protected Json $payload;
    protected ?Response $response;

    /**
     * Instantiate a new resource instance.
     *
     * @param Json|array<int|string, mixed>|string $payload
     * @param Response|null $response
     */
    final public function __construct(Json|array|string $payload = '', Response $response = null)
    {
        if ($payload instanceof Json) {
            $this->payload = $payload;
        } else {
            $this->payload = is_array($payload)
                ? Json::fromArray($payload)
                : Json::of($payload);
        }

        $this->response = $response;
    }

    /**
     * Create a new resource instance from an array.
     *
     * @param array<string, mixed> $payload
     * @return static
     */
    public static function fromArray(array $payload = []): static
    {
        return new static($payload);
    }

    /**
     * Create a new resource instance from a Horizon response.
     *
     * @param Response $response
     * @throws UnexpectedValueException
     * @return static
     */
    public static function fromResponse(Response $response): static
    {
        return new static(strval($response->getBody()), $response);
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
     * A sanity check for the API consumer: Does this resource represent a
     * failed request? This has been added for convenience but usage
     * is not recommended.
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
