<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

abstract class Optional implements XdrOptional
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    protected mixed $value = null;

    /**
     * Create a new instance of this class with no value.
     *
     * @return static
     */
    public static function none(): static
    {
        return new static();
    }

    /**
     * Has a value been set?
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return isset($this->value) && !is_null($this->value);
    }

    /**
     * Get the value.
     *
     * @return mixed
     */
    protected function getValue(): mixed
    {
        return $this->hasValue() ? $this->value : null;
    }

    /**
     * Set the value.
     *
     * @param mixed $value
     *
     * @return static
     */
    protected function withValue($value): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->value = is_object($value)
            ? Copy::deep($value)
            : $value;

        return $clone;
    }

    /**
     * Determine if there should be a value.
     *
     * @return bool
     */
    public function hasValueForXdr(): bool
    {
        return $this->hasValue();
    }

    /**
     * The optional value to be encoded as XDR bytes.
     *
     * @return int
     */
    public function getXdrValue(): mixed
    {
        return $this->getValue();
    }

    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    abstract public static function getXdrValueType(): string;

    /**
     * If the value type requires a designated length specify it here.
     *
     * @return int|null
     */
    public static function getXdrValueLength(): ?int
    {
        return null;
    }

    /**
     * Create a new instance of this class from XDR.
     *
     * @return static
     */
    public static function newFromXdr(bool $hasValue, mixed $value): static
    {
        return (new static())->withValue($value);
    }
}
