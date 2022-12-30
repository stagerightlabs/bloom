<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use ArrayAccess;
use Countable;
use Iterator;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrArray;
use StageRightLabs\PhpXdr\XDR;

/**
 * A generic array object for working with lists of XDR objects. The XdrArray
 * interface implementation allows child classes to be encoded and decoded
 * as XDR. The other interfaces allow PHP to treat child classes as arrays.
 * Note: Associative arrays, or hash maps, are not supported here.
 *
 * @template TValue
 * @implements Iterator<int, TValue>
 * @implements ArrayAccess<int, TValue>
 */
abstract class Arr implements XdrArray, Iterator, Countable, ArrayAccess
{
    /**
     * Ensure there is always a valid underlying array.
     *
     * @param array<mixed> $arr
     */
    final public function __construct($arr = [])
    {
        $this->arr = $arr;
    }

    /**
     * @var int
     */
    protected int $position = 0;

    /**
     * @var array<int, TValue>
     */
    protected array $arr;

    /**
     * Wrap an array with this class. Same as 'make' but accepts mixed values.
     *
     * @param TValue|array<int, TValue> $value
     * @return static
     */
    public static function of($value): static
    {
        if (!is_array($value)) {
            $value = [$value];
        }

        return static::make($value);
    }

    /**
     * Wrap an array with this class.
     *
     * @param array<int, TValue> $value
     * @return static
     */
    public static function make(array $value): static
    {
        $arr = (new static())->withArray($value);
        $arr->rewind();

        return $arr;
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
     * Add a value to the underlying array at a given index.
     *
     * @param int $index
     * @param mixed $value
     * @return static
     */
    public function with(int $index, mixed $value): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->arr = $this->clone($this->arr);
        $clone->arr[$index] = $value;

        return $clone;
    }

    /**
     * Add a value to the underlying array.
     *
     * @param mixed $value
     * @return static
     */
    public function push(mixed $value): static
    {
        // Ensure we have enough room remaining
        $max = $this->getMaxLength();
        if (count($this->arr) >= $max) {
            $class = get_called_class();
            throw new UnexpectedValueException("Array capacity reached: {$class} limit is {$max} elements.");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->arr = $this->clone($this->arr);
        array_push($clone->arr, $value);

        return $clone;
    }

    /**
     * The XDR encoding type for array members.
     *
     * @return string
     */
    abstract public static function getXdrType(): string;

    /**
     * The maximum number of allowed array members.
     *
     * @return int
     */
    public static function getMaxLength(): int
    {
        return XDR::MAX_LENGTH;
    }

    /**
     * Get the array that will be encoded into XDR bytes.
     *
     * @return array<int, TValue>
     */
    public function getXdrArray(): array
    {
        return $this->toArray();
    }

    /**
     * Specify a length to encode as a fixed length array. Otherwise return
     * null to encode as a variable length array.
     *
     * @return int|null
     */
    public static function getXdrLength(): ?int
    {
        return null;
    }

    /**
     * If the underlying content type requires a length it can be specified
     * with this method.
     *
     * @return integer|null
     */
    public static function getXdrTypeLength(): ?int
    {
        return null;
    }

    /**
     * Create a new instance of this class from XDR.
     *
     * @param array<int, TValue> $arr
     * @return static
     */
    public static function newFromXdr(array $arr): static
    {
        return static::of($arr);
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
     * Set the value of the array.
     *
     * @param array<int, TValue> $arr
     * @throws InvalidArgumentException
     * @return static
     */
    protected function withArray(array $arr): static
    {
        $length = count($arr);
        $max = $this->getMaxLength();

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} length array where the maximum allowed is {$max}");
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->arr = $this->clone(array_values($arr));

        return $clone;
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
     * Clone an array of objects.
     *
     * @param array<int, TValue> $value
     * @return array<int, TValue>
     */
    protected function clone(array $value): array
    {
        $arr = [];

        foreach ($value as $key => $value) {
            if (is_array($value)) {
                $value = $this->clone($value);
            }

            if (is_object($value)) {
                $value = Copy::deep($value);
            }

            $arr[intval($key)] = $value;
        }

        return $arr;
    }
}
