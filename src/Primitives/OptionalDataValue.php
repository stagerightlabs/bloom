<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalDataValue extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return DataValue::class;
    }

    /**
     * Create an instance from an DataValue.
     *
     * @param OptionalDataValue|DataValue|string $dataValue
     * @return static
     * @throws InvalidArgumentException
     */
    public static function some(OptionalDataValue|DataValue|string $dataValue): static
    {
        if ($dataValue instanceof OptionalDataValue) {
            return self::none()->withValue($dataValue->unwrap());
        }

        if (is_string($dataValue)) {
            $dataValue = DataValue::of($dataValue);
        }

        return self::none()->withValue($dataValue);
    }

    /**
     * Return the optional value.
     *
     * @return DataValue|null
     */
    public function unwrap(): ?DataValue
    {
        return $this->getValue();
    }
}
