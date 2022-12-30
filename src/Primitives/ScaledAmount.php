<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use Brick\Math\BigDecimal;
use Brick\Math\BigNumber;
use Brick\Math\Exception\MathException;
use StageRightLabs\Bloom\Exception\MathException as BloomMathException;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\Number;

/**
 * A wrapper class for Brick/Math BigDecimal. The original documentation comments
 * have been included where possible with some contextual adjustments.
 *
 * @see https://github.com/brick/math/blob/f8f9880bc6a230be29481f665ab2c6b7d3840b28/src/BigDecimal.php
 */
class ScaledAmount
{
    use NoChanges;

    /**
     * The underlying BigDecimal instance.
     *
     * @var BigDecimal
     */
    protected $bigDecimal;

    /**
     * Create a new instance of this class. To emulate the BigInteger class
     * we will require that the constructor remain protected so that
     * instantiation can only occur with static method calls.
     *
     * @codeCoverageIgnore
     * @param BigDecimal $bigDecimal
     */
    final protected function __construct($bigDecimal)
    {
        $this->bigDecimal = $bigDecimal;
    }

    /**
     * Creates a BigDecimal of the given value.
     *
     * @param BigNumber|Int64|int|float|string $value
     * @throws BloomMathException If the value cannot be converted to a ScaledAmount
     * @return static
     */
    public static function of($value): static
    {
        if (is_string($value)) {
            $value = str_replace(',', '', $value);
        }

        if ($value instanceof Int64) {
            $value = Number::scale($value);
        }

        try {
            $scaledAmount = new static(BigDecimal::of($value)->toScale(7));
        } catch (MathException $ex) {
            throw BloomMathException::fromException($ex);
        }

        if (!Number::isValidAmount($scaledAmount->toNativeString(), $allowZero = true)) {
            throw new BloomMathException("Attempting to create a scaled amount from invalid value '{$value}'");
        }

        return $scaledAmount;
    }

    /**
     * Check if this amount is equal to the given amount.
     *
     * @param ScaledAmount|BigNumber|int|float|string $scaledAmount
     * @return bool
     */
    public function isEqualTo(ScaledAmount|BigNumber|int|float|string $scaledAmount): bool
    {
        if (!$scaledAmount instanceof ScaledAmount) {
            $scaledAmount = ScaledAmount::of($scaledAmount);
        }

        return $this->bigDecimal->compareTo($scaledAmount->toBigDecimal()) == 0;
    }

    /**
     * Descale this value into an Int64.
     *
     * @return Int64
     */
    public function descale(): Int64
    {
        return Int64::of(Number::descale($this->toNativeString()));
    }

    /**
     * Return a string representation of the scaled amount.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return strval($this);
    }

    /**
     * Allow this scaled amount to be cast as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->bigDecimal->__toString();
    }

    /**
     * Return this value as a BigDecimal instance.
     *
     * @return BigDecimal
     */
    public function toBigDecimal(): BigDecimal
    {
        return $this->bigDecimal;
    }
}
