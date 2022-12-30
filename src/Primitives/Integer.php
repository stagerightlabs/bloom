<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use Brick\Math\BigInteger;
use Brick\Math\BigNumber;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NegativeNumberException;
use Brick\Math\Exception\NumberFormatException;
use StageRightLabs\Bloom\Exception\MathException as BloomMathException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;

/**
 * A wrapper class for Brick/Math BigInteger. The original documentation comments
 * have been included where possible with some contextual adjustments.
 *
 * @see https://github.com/brick/math/blob/f8f9880bc6a230be29481f665ab2c6b7d3840b28/src/BigInteger.php
 */
abstract class Integer
{
    use NoChanges;

    /**
     * The underlying BigInteger instance.
     *
     * @var BigInteger
     */
    protected $bigInteger;

    /**
     * Create a new instance of this class. To emulate the BigInteger class
     * we will require that the constructor remain protected so that
     * instantiation can only occur with static method calls.
     *
     * @codeCoverageIgnore
     * @param BigInteger $bigInteger
     */
    final protected function __construct($bigInteger)
    {
        $this->bigInteger = $bigInteger;
    }

    /**
     * Creates a BigInteger of the given value.
     *
     * Throws an exception if the value cannot be converted to a BigInteger.
     *
     * @param  \StageRightLabs\Bloom\Primitives\Integer|BigNumber|int|float|string $value
     * @throws BloomMathException
     * @return static
     */
    public static function of($value): static
    {
        if (is_object($value) && $value instanceof Integer) {
            $value = $value->toBigInteger();
        }

        try {
            return new static(BigInteger::of($value));
        } catch (MathException $ex) {
            throw BloomMathException::fromException($ex);
        }
    }

    /**
     * Translates a string of bytes containing the binary representation of a BigInteger into a BigInteger.
     *
     * The input string is assumed to be in big-endian byte-order: the most significant byte is in the zeroth element.
     *
     * If `$signed` is true, the input is assumed to be in two's-complement representation, and the leading bit is
     * interpreted as a sign bit. If `$signed` is false, the input is interpreted as an unsigned number, and the
     * resulting BigInteger will always be positive or zero.
     *
     * This method can be used to retrieve a number exported by `toBytes()`, as long as the `$signed` flags match.
     *
     * @param string $value  The byte string.
     * @param bool   $signed Whether to interpret as a signed number in two's-complement representation with a leading
     *                       sign bit.
     * @throws NumberFormatException If the string is empty.
     * @return static
     */
    public static function fromBytes(string $value, bool $signed = true): static
    {
        return new static(BigInteger::fromBytes($value, true));
    }

    /**
     * Returns a string of bytes containing the binary representation of this BigInteger.
     *
     * The string is in big-endian byte-order: the most significant byte is in the zeroth element.
     *
     * If `$signed` is true, the output will be in two's-complement representation, and a sign bit will be prepended to
     * the output. If `$signed` is false, no sign bit will be prepended, and this method will throw an exception if the
     * number is negative.
     *
     * The string will contain the minimum number of bytes required to represent this BigInteger, including a sign bit
     * if `$signed` is true.
     *
     * This representation is compatible with the `fromBytes()` factory method, as long as the `$signed` flags match.
     *
     * @param bool $signed Whether to output a signed number in two's-complement representation with a leading sign bit.
     * @throws NegativeNumberException If $signed is false, and the number is negative.
     * @return string
     */
    public function toBytes($signed = true): string
    {
        return $this->bigInteger->toBytes($signed);
    }

    /**
     * Returns the number of bits in the minimal two's-complement representation of this BigInteger, excluding a sign bit.
     *
     * For positive BigIntegers, this is equivalent to the number of bits in the ordinary binary representation.
     * Computes (ceil(log2(this < 0 ? -this : this+1))).
     *
     * @return int
     */
    public function getBitLength(): int
    {
        return $this->bigInteger->getBitLength();
    }

    /**
     * Checks if this number is equal to the given one.
     *
     * @param \StageRightLabs\Bloom\Primitives\Integer|BigNumber|int|float|string $that
     * @return bool
     */
    public function isEqualTo($that): bool
    {
        if (is_object($that) && $that instanceof Integer) {
            $that = $that->toBigInteger();
        }

        return $this->bigInteger->isEqualTo($that);
    }

    /**
     * Returns the sum of this number and the given one.
     *
     * @param \StageRightLabs\Bloom\Primitives\Integer|BigNumber|int|float|string $that The number to add. Must be convertible to a BigInteger.
     * @throws MathException If the number is not valid, or is not convertible to a BigInteger.
     * @return static The result.
     */
    public function plus($that): static
    {
        if ($that instanceof Integer) {
            $that = $that->toBigInteger();
        }

        /** @var static */
        return get_called_class()::of($this->bigInteger->plus($that));
    }

    /**
     * Returns the difference of this number and the given one.
     *
     * @param \StageRightLabs\Bloom\Primitives\Integer|BigNumber|int|float|string $that The number to subtract. Must be convertible to a BigInteger.
     * @throws MathException If the number is not valid, or is not convertible to a BigInteger.
     * @return \StageRightLabs\Bloom\Primitives\Integer The result.
     */
    public function minus($that): Integer
    {
        if (is_object($that) && $that instanceof Integer) {
            $that = $that->toBigInteger();
        }

        return get_called_class()::of($this->bigInteger->minus($that));
    }

    /**
     * Returns the exact value of this number as a native integer.
     *
     * If this number cannot be converted to a native integer without losing precision, an exception is thrown.
     * Note that the acceptable range for an integer depends on the platform and differs for 32-bit and 64-bit.
     *
     * @throws \Brick\Math\Exception\MathException If this number cannot be exactly converted to a native integer.
     * @return int The converted value.
     */
    public function toNativeInt(): int
    {
        return $this->bigInteger->toInt();
    }

    /**
     * Checks if this number is strictly greater than the given one.
     *
     * @param \StageRightLabs\Bloom\Primitives\Integer|BigNumber|int|float|string $that $that
     *
     * @return bool
     */
    public function isGreaterThan($that): bool
    {
        if (is_object($that) && $that instanceof Integer) {
            $that = $that->toBigInteger();
        }

        return $this->bigInteger->isGreaterThan($that);
    }

    /**
     * Checks if this number is strictly negative.
     *
     * @return bool
     */
    public function isNegative(): bool
    {
        return $this->bigInteger->getSign() < 0;
    }

    /**
     * The string representation of this number.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->bigInteger->__toString();
    }

    /**
     * The string representation of this number.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return $this->__toString();
    }

    /**
     * Return the underlying BigInteger instance.
     *
     * @return BigInteger
     */
    public function toBigInteger(): BigInteger
    {
        /** @var BigInteger */
        $clone = Copy::deep($this->bigInteger);

        return $clone;
    }
}
