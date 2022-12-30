<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use Brick\Math\BigDecimal;
use Brick\Math\BigInteger;
use Brick\Math\Exception\IntegerOverflowException;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\Bloom\Utility\Number;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Price implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Int32 $n; // numerator
    protected Int32 $d; // denominator. Price = amount B / amount A = numerator / denominator

    /**
     * Create a new instance from a numerator and a denominator.
     *
     * @param Int32|int $n
     * @param Int32|int $d
     * @return static
     */
    public static function of(Int32|int $n, Int32|int $d): static
    {
        $price = new static();
        $price->n = Int32::of($n);
        $price->d = Int32::of($d);

        return $price;
    }

    /**
     * Create a new instance from a string.
     *
     * This can be either a decimal value ("3.75") or a fraction ("15/4").
     *
     * @param string $decimal
     * @return static
     * @throws InvalidArgumentException
     * @throws IntegerOverflowException
     */
    public static function rationalize(string $decimal): static
    {
        // Handle a fractional string.
        if (str_contains($decimal, '/')) {
            $parts = explode('/', $decimal);

            // Check for parts that are less than one
            if ($parts[0] < 1 || $parts[1] < 1) {
                throw new InvalidArgumentException('Price ratios must have a numerator and a denominator greater than 1');
            }

            // Check for parts that are decimals - we will only accept integers
            if (Number::isDecimal($parts[0]) || Number::isDecimal($parts[1])) {
                throw new InvalidArgumentException('Price ratios cannot be calculated from decimal values');
            }

            return (new static())
                ->withNumerator(intval($parts[0]))
                ->withDenominator(intval($parts[1]));
        }

        // Otherwise assume it is a decimal string.
        $numerator = BigDecimal::of($decimal)->getUnscaledValue();
        $denominator = BigInteger::of(pow(10, Number::decimalPlaceCount($decimal)));
        $gcd = $numerator->gcd($denominator);

        return (new static())
            ->withNumerator($numerator->dividedBy($gcd)->toInt())
            ->withDenominator($denominator->dividedBy($gcd)->toInt());
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->n)) {
            throw new InvalidArgumentException('The price does not have a numerator');
        }

        if (!isset($this->d)) {
            throw new InvalidArgumentException('The price does not have a denominator');
        }

        $xdr->write($this->n)->write($this->d);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $price = new static();
        $price->n = $xdr->read(Int32::class);
        $price->d = $xdr->read(Int32::class);

        return $price;
    }

    /**
     * Get the numerator.
     *
     * @return Int32
     */
    public function getNumerator(): Int32
    {
        return $this->n;
    }

    /**
     * Accept a numerator.
     *
     * @param Int32|int $n
     * @return static
     */
    public function withNumerator(Int32|int $n): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->n = Int32::of($n);

        return $clone;
    }

    /**
     * Get the denominator.
     *
     * @return Int32
     */
    public function getDenominator(): Int32
    {
        return $this->d;
    }

    /**
     * Accept a denominator.
     *
     * @param Int32|int $d
     * @return static
     */
    public function withDenominator(Int32|int $d): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->d = Int32::of($d);

        return $clone;
    }

    /**
     * Create a new instance from a string representing a decimal value.
     *
     * @param string $decimal
     * @return static
     */
    public static function fromNativeString(string $decimal): static
    {
        return self::rationalize($decimal);
    }

    /**
     * Return the price as a decimal string with the maximum allowed number
     * of decimal places. In certain situations this conversion will lead
     * to a loss of precision. Use fractional values when possible.
     *
     * @return string
     */
    public function toNativeString(): string
    {
        return BigDecimal::of($this->getNumerator()->toNativeInt())
            ->dividedBy(
                $this->getDenominator()->toNativeInt(),
                Number::SCALE,
                \Brick\Math\RoundingMode::HALF_DOWN
            )->__toString();
    }
}
