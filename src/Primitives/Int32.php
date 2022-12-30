<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

final class Int32 implements XdrTypedef
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    public const MAX_VALUE = 2147483647;
    public const MIN_VALUE = -2147483647;
    private int $value;

    /**
     * Create a new Int32 instance by wrapping an integer.
     *
     * @param Int32|int $number
     * @return static
     */
    public static function of(Int32|int $number): static
    {
        if ($number instanceof Int32) {
            $number = $number->toNativeInt();
        }

        if ($number > self::MAX_VALUE) {
            throw new InvalidArgumentException('A Int32 cannot be larger than ' . self::MAX_VALUE);
        }

        if ($number < self::MIN_VALUE) {
            throw new InvalidArgumentException('A UInt32 cannot be lower than ' . self::MIN_VALUE);
        }

        return (new static())->withValue($number);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->value, XDR::INT);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withValue($xdr->read(XDR::INT));
    }

    /**
     * Get the value.
     *
     * @return int
     */
    public function toNativeInt(): int
    {
        return $this->value;
    }

    /**
     * Set the value.
     *
     * @param int $value
     *
     * @return Int32
     */
    private function withValue(int $value): Int32
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }
}
