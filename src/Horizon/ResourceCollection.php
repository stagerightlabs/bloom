<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use ArrayAccess;
use Countable;
use Iterator;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Json;

/**
 * @template TValue
 * @implements Iterator<int, TValue>
 * @implements ArrayAccess<int, TValue>
 */
class ResourceCollection implements Iterator, Countable, ArrayAccess
{
    /**
     * Properties
     */
    protected Json $payload;
    protected ?Response $response;
    protected int $position = 0;

    /**
     * @var array<int, TValue>
     */
    protected array $arr;

    /**
     * Instantiate a new resource collection instance.
     *
     * @param Json|array<int|string, mixed>|string $payload
     * @param Response|null $response
     */
    final public function __construct(Json|array|string $payload = '', Response $response = null)
    {
        // Accept the Json Payload
        if (!$payload instanceof Json) {
            $payload = is_array($payload)
                ? Json::fromArray($payload)
                : Json::of($payload);
        }
        $this->payload = $payload;

        // Accept the response if present
        $this->response = $response;

        /** @var callable */
        $map = [$this->getResourceClass(), 'fromArray'];

        // Translate the records into individual resource classes
        $this->arr = [];
        foreach ($this->payload->getArray('_embedded.records') ?? [] as $resource) {
            $this->arr[] = call_user_func($map, $resource);
        }
    }

    /**
     * The type of resource that makes up this collection.
     *
     * @return class-string
     */
    protected function getResourceClass(): string
    {
        return Resource::class;
    }

    /**
     * Create a new resource collection instance from an array.
     *
     * @param array<string, mixed> $payload
     * @return static
     */
    public static function fromArray(array $payload = []): static
    {
        return new static($payload);
    }

    /**
     * Create a new resource collection instance from a Horizon response.
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
     * Return the element at a given index.
     *
     * @param int $index
     * @param mixed $default
     * @return mixed
     */
    public function get(int $index, mixed $default = null): mixed
    {
        return array_key_exists($index, $this->arr)
            ? $this->arr[$index]
            : $default;
    }

    /**
     * The number of elements in the array. Part of the Countable interface.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->arr);
    }

    /**
     * The element at the current array position. Part of the Iterator interface.
     *
     * @return TValue
     */
    public function current(): mixed
    {
        return $this->arr[$this->position];
    }

    /**
     * The current position.  Part of the Iterator interface.
     *
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Advance to the next position. Part of the Iterator interface.
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Return to the first position in the array.  Part of the Iterator interface.
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Ensure there is a valid element at the current position.
     * Part of the Iterator interface.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return array_key_exists($this->position, $this->arr);
    }

    /**
     * Does an offset exist in the underlying array? Part of the ArrayAccess interface.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->arr[$offset]);
    }

    /**
     * Retrieve an offset from the underlying array. Part of the ArrayAccess interface.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->arr[$offset])
            ? $this->arr[$offset]
            : null;
    }

    /**
     * Assign a value to the specified offset. Part of the ArrayAccess interface.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->arr[] = $value;
        } else {
            $this->arr[intval($offset)] = $value;
        }
    }

    /**
     * Unset an offset. Part of the ArrayAccess interface.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->arr[$offset]);
    }

    /**
     * Determine whether or not the array is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    /**
     * Determine whether or not the array is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Return the array.
     *
     * @return array<int, TValue>
     */
    public function toArray(): array
    {
        return $this->arr;
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

    /**
     * Return the 'self' link.
     *
     * @return string|null
     */
    public function getSelfLink(): ?string
    {
        return $this->getLink('self');
    }
}
