<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

abstract class Enumeration implements XdrEnum
{
    /**
     * Properties
     */
    use NoConstructor;
    protected ?int $selection = null;

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    abstract public static function getOptions(): array;

    /**
     * Check to see if a selection is valid. Check the current selection if
     * no selection parameter has been provided.
     *
     * @param int $selection
     * @return bool
     */
    public static function isValid(int|string $selection): bool
    {
        if (is_string($selection)) {
            return in_array($selection, array_values(static::getOptions()), true);
        }

        return in_array($selection, array_keys(static::getOptions()), true);
    }

    /**
     * Return the value to be encoded as XDR bytes.
     *
     * @return integer
     */
    public function getXdrSelection(): int
    {
        return $this->getIndex();
    }

    /**
     * Create a new instance of this class from XDR.
     *
     * @param int $value
     * @return static
     */
    public static function newFromXdr(int $value): static
    {
        return (new static())->withSelection($value);
    }

    /**
     * Determine if a value is a member of the ENUM options.
     *
     * @param integer $value
     * @return boolean
     */
    public function isValidXdrSelection(int $value): bool
    {
        return static::isValid($value);
    }

    /**
     * Return the selected enumeration index.
     *
     * @throws InvalidArgumentException
     * @return int
     */
    protected function getIndex(): int
    {
        $selection = $this->selection ?? $this->getDefaultSelection();

        if (is_null($selection)) {
            $class = get_called_class();
            throw new InvalidArgumentException("{$class} cannot have a null selection");
        }

        return $selection;
    }

    /**
     * Return the selected enumeration value.
     *
     * @return string
     */
    protected function getValue(): string
    {
        return $this->getOptions()[$this->getIndex()];
    }

    /**
     * Return the enumeration as a string value.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * Set the selection from a value.
     *
     * @param string $value
     * @throws InvalidArgumentException
     * @return static
     */
    protected function withValue(string $value): static
    {
        $selection = array_search($value, $this->getOptions(), true);

        if (is_bool($selection)) {
            $class = get_called_class();
            $exception = new InvalidArgumentException("Attempting to set an invalid value for Enumerator {$class}.");
            $exception->setOptions($this->getOptions());

            throw $exception;
        }

        return $this->withSelection($selection);
    }

    /**
     * Change the current selection.
     *
     * @param int $selection
     * @throws InvalidArgumentException
     * @return static
     */
    protected function withSelection(int $selection): static
    {
        if (!static::isValid($selection)) {
            $class = static::class;
            $exception = new InvalidArgumentException("{$selection} is not a valid '{$class}' selection.");
            $exception->setOptions(array_values($this->getOptions()));
            throw $exception;
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->selection = $selection;

        return $clone;
    }

    /**
     * Specify a default selection as a fallback.
     *
     * @return int|null
     */
    protected static function getDefaultSelection(): ?int
    {
        return null;
    }

    /**
     * Create a new instance pre-defined with a selected value.
     *
     * @param int|string $type
     * @return static
     */
    public static function of(int|string $type): static
    {
        return is_int($type)
            ? (new static())->withSelection($type)
            : (new static())->withValue($type);
    }

    /**
     * Determine if the enumeration content matches a given value.
     *
     * When given a string it will compare against the selected value of
     * the enum, when given an int it will check the selection index.
     *
     * @param int|string $comparator
     * @return bool
     */
    public function is(int|string $comparator): bool
    {
        if (is_int($comparator)) {
            return $this->selection === $comparator;
        }

        return $this->getValue() === $comparator;
    }
}
