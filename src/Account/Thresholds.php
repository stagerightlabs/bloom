<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class Thresholds implements XdrTypedef
{
    /**
     * Properties
     */
    use NoConstructor;
    use NoChanges;
    public const CATEGORY_LOW = 'low';
    public const CATEGORY_MEDIUM = 'medium';
    public const CATEGORY_HIGH = 'high';
    public const MAX_BYTE_LENGTH = 4;
    public string $raw;

    /**
     * Create a new instance from a set of integers.
     *
     * @param int $master
     * @param int $low
     * @param int $medium
     * @param int $high
     * @return static
     */
    public static function of(int $master = 1, int $low = 0, int $medium = 0, int $high = 0): static
    {
        if ($master > 255 || $low > 255 || $medium > 255 || $high > 255) {
            throw new InvalidArgumentException("Threshold values ({$master},{$low},{$medium},{$high}) cannot be higher than 255.");
        }

        return (new static())->withRaw(pack('C*', $master, $low, $medium, $high));
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->getRaw(), XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withRaw($xdr->read(XDR::OPAQUE_FIXED, length: self::MAX_BYTE_LENGTH));
    }

    /**
     * Return the underlying value.
     *
     * @return string
     */
    public function getRaw(): string
    {
        return $this->raw;
    }

    /**
     * Set the value of the string.
     *
     * @param string $code
     * @throws InvalidArgumentException
     * @return static
     */
    public function withRaw(string $code): static
    {
        $length = strlen($code);
        $max = self::MAX_BYTE_LENGTH;

        if ($length > $max) {
            throw new InvalidArgumentException("Attempting to use a {$length} byte string for thresholds where only {$max} bytes are allowed");
        }

        $clone = Copy::deep($this);
        $clone->raw = $code;

        return $clone;
    }

    /**
     * Return the thresholds as a hexadecimal string.
     *
     * @return string
     */
    public function toHex(): string
    {
        return bin2hex($this->raw);
    }

    /**
     * Return the master threshold value as an integer.
     *
     * @return int
     */
    public function getMasterThreshold(): int
    {
        if (!isset($this->raw)) {
            return 1;
        }

        $value = unpack('C', $this->raw, 0);

        // @codeCoverageIgnoreStart
        if ($value === false) {
            throw new UnexpectedValueException('Could not unpack the master threshold value');
        }
        // @codeCoverageIgnoreEnd

        return array_shift($value);
    }

    /**
     * Return the master threshold value as an integer.
     *
     * @return int
     */
    public function getLowThreshold(): int
    {
        if (!isset($this->raw)) {
            return 0;
        }

        $value = unpack('C', $this->raw, 1);

        // @codeCoverageIgnoreStart
        if ($value === false) {
            throw new UnexpectedValueException('Could not unpack the low threshold value');
        }
        // @codeCoverageIgnoreEnd

        return array_shift($value);
    }

    /**
     * Return the master threshold value as an integer.
     *
     * @return int
     */
    public function getMediumThreshold(): int
    {
        if (!isset($this->raw)) {
            return 0;
        }

        $value = unpack('C', $this->raw, 2);

        // @codeCoverageIgnoreStart
        if ($value === false) {
            throw new UnexpectedValueException('Could not unpack the medium threshold value');
        }
        // @codeCoverageIgnoreEnd

        return array_shift($value);
    }

    /**
     * Return the master threshold value as an integer.
     *
     * @return int
     */
    public function getHighThreshold(): int
    {
        if (!isset($this->raw)) {
            return 0;
        }

        $value = unpack('C', $this->raw, 3);

        // @codeCoverageIgnoreStart
        if ($value === false) {
            throw new UnexpectedValueException('Could not unpack the high threshold value');
        }
        // @codeCoverageIgnoreEnd

        return array_shift($value);
    }
}
