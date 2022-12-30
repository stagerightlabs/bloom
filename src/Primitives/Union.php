<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

abstract class Union implements XdrUnion
{
    /**
     * Properties
     */
    use NoConstructor;
    protected mixed $value;
    protected Enumeration|XdrEnum|int|bool $discriminator;

    /**
     * Return the discriminator.
     *
     * @return XdrEnum|int|bool
     */
    public function getXdrDiscriminator(): XdrEnum|int|bool
    {
        return $this->getDiscriminator();
    }

    /**
     * What type of discriminator is being used in this union?
     * Allowed types are XDR::INT, XDR::UINT, XDR::BOOL or
     * the name of a class that implements XdrEnum.
     *
     * @return string
     */
    abstract public static function getXdrDiscriminatorType(): string;

    /**
     * The possible value types for this union.
     *
     * @return array<int|string, string>
     */
    abstract protected static function arms(): array;

    /**
     * Return the 'arms' that have been defined in this union.
     *
     * @return array<int|bool|string, string>
     */
    public static function getXdrArms(): array
    {
        return static::arms();
    }

    /**
     * Return the encoding type for the selected value.
     *
     * @return string
     */
    public static function getXdrDiscriminatedValueType(int|bool|XdrEnum $discriminator): string
    {
        if ($discriminator instanceof Enumeration) {
            $discriminator = strval($discriminator);
        }

        if ($discriminator instanceof XdrEnum) {
            $discriminator = $discriminator->getXdrSelection();
        }

        return static::arms()[$discriminator];
    }

    /**
     * If the value type requires a designated length specify it here.
     *
     * @return int|null
     */
    public static function getXdrDiscriminatedValueLength(int|bool|XdrEnum $discriminator): ?int
    {
        return null;
    }

    /**
     * Return the selected value to be encoded as XDR bytes.
     *
     * @return mixed
     */
    public function getXdrValue(): mixed
    {
        return $this->value;
    }

    /**
     * Create a new instance of this class from XDR.
     *
     * @return static
     */
    public static function newFromXdr(int|bool|XdrEnum $discriminator, mixed $value): static
    {
        $union = new static();
        $union->discriminator = $discriminator;
        $union->value = $value;

        return $union;
    }

    /**
     * Get the value of the discriminator.
     *
     * @return Enumeration|XdrEnum|int|bool
     */
    protected function getDiscriminator(): Enumeration|XdrEnum|int|bool
    {
        return $this->discriminator;
    }
}
