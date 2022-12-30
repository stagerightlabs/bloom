<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Utility;

use StageRightLabs\Bloom\Exception\InvalidAmountException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\Integer;

class Number
{
    /**
     * The largest possible Int64.
     */
    public const MAX_INT64 = '9223372036854775807';

    /**
     * The number of decimal places in a 'scaled' value.
     */
    public const SCALE = 7;

    /**
     * Determine if a numerical amount is valid for use in the ledger.
     *
     * A valid amount must be a string that represents either zero or a positive
     * arbitrary precision number. The value cannot be higher than MAX_INT64,
     * and it may not have a scale of more than seven decimal places.
     *
     * @see https://github.com/stellar/js-stellar-base/blob/1b5b2d463640eb05b9918e57647634a0a86e6123/src/operation.js#L409
     * @param string $amount
     * @param bool $allowZero
     * @return bool
     */
    public static function isValidAmount(string $amount, bool $allowZero = false): bool
    {
        // check decimal place count
        if (self::decimalPlaceCount($amount) > self::SCALE) {
            return false;
        }

        try {
            $integer = self::descale($amount);
        } catch (\Throwable $th) {
            return false;
        }

        // check for zero
        if (!$allowZero && $integer->isEqualTo(0)) {
            return false;
        }

        // check for negative numbers
        if ($integer->isNegative()) {
            return false;
        }

        return true;
    }

    /**
     * Remove the scale from a string amount.
     *
     * An 'amount' is a string representing a decimal with no more than seven
     * decimal places. Here we return it as an integer with no decimal point.
     *
     * @param string $amount
     * @throws InvalidAmountException
     * @return Int64
     */
    public static function descale(string $amount): Int64
    {
        // Remove any commas that may be present in the string
        $clean = str_replace(',', '', $amount);

        // Use the decimal place to separate the number into an 'upper' and 'lower' section
        $segments = explode('.', $clean);
        $upper = $segments[0];
        $lower = isset($segments[1]) ? $segments[1] : '';

        // The precision is the number of decimal places
        $precision = strlen($lower);

        // The precision cannot be greater than self::SCALE decimal places
        if ($precision > self::SCALE) {
            throw new InvalidAmountException("Amount '{$amount}' has more than seven decimal places.");
        }

        // Pad the lower section with zeros to get a precision of self::SCALE decimal places.
        $lower .= str_repeat('0', self::SCALE - $precision);

        // Combine the upper and lower segments together without a decimal place.
        $amount = $upper . $lower;

        // Convert this number string into an Int64 instance.
        $integer = Int64::of($amount);

        if ($integer->isGreaterThan(self::MAX_INT64)) {
            throw new InvalidAmountException("Amount '{$amount}' exceeds the maximum allowed ledger value.");
        }

        return $integer;
    }

    /**
     * Convert an integer into a string amount with seven decimal places.
     *
     * @param \StageRightLabs\Bloom\Primitives\Integer $integer
     * @param bool $includeCommas
     * @return string
     */
    public static function scale(Integer $integer, bool $includeCommas = true): string
    {
        // Generate a string representation of the integer
        $string = strval($integer);

        // If there are fewer than seven digits we will pad with zeros
        $length = strlen($string);
        if ($length < self::SCALE) {
            $string = str_repeat('0', self::SCALE - $length) . $string;
        }

        // Determine the left side of the number
        $left = substr($string, 0, -self::SCALE);

        // Maybe insert commas
        if ($includeCommas) {
            $count = 0;
            $left = array_reduce(str_split(strrev($left)), function ($carry, $digit) use (&$count) {
                $count++;

                if ($count % 3 == 0) {
                    $digit = ',' . $digit;
                }

                return $digit . $carry;
            }, '');

            if (substr($left, 0, 1) == ',') {
                $left = substr($left, 1);
            }
        }

        // Determine the right side of the number
        $right = substr($string, -self::SCALE);

        // Combine left and right together with a decimal place
        $combined = $left . '.' . $right;

        return $combined;
    }

    /**
     * Count the number of decimal places in a string amount.
     *
     * @param string $amount
     * @return int
     */
    public static function decimalPlaceCount(string $amount): int
    {
        $clean = str_replace(',', '', $amount);
        $segments = explode('.', $clean);
        $lower = isset($segments[1]) ? $segments[1] : '';

        return strlen($lower);
    }

    /**
     * Remove leading and trailing zeros from a string amount.
     *
     * @param string $amount
     * @return string
     */
    public static function trimZeroes(string $amount): string
    {
        return rtrim(trim($amount, '0'), '.');
    }

    /**
     * Confirm whether or not a number is a decimal value.
     *
     * @see https://beamtic.com/check-if-number-is-decimal-php
     * @param string|float|int $number
     * @return bool
     */
    public static function isDecimal(string|float|int $number): bool
    {
        if (is_float($number)) {
            return true;
        }

        if (is_string($number) && str_contains($number, '.')) {
            return true;
        }

        return is_numeric($number) && intval($number) != $number;
    }
}
