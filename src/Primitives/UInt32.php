<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

final class UInt32 implements XdrTypedef
{
    use NoChanges;
    use NoConstructor;

    public const MAX_VALUE = 4294967295; // pow(2, 32) - 1
    public const MIN_VALUE = 0;
    protected int $value;

    /**
     * Create a new UInt32 instance by wrapping an integer.
     *
     * @throws InvalidArgumentException
     * @return static
     */
    public static function of(UInt32|int $number): static
    {
        if ($number instanceof UInt32) {
            $number = $number->toNativeInt();
        }

        if ($number > self::MAX_VALUE) {
            throw new InvalidArgumentException('A UInt32 cannot be larger than ' . self::MAX_VALUE);
        }

        if ($number < self::MIN_VALUE) {
            throw new InvalidArgumentException('A UInt32 cannot be lower than ' . self::MIN_VALUE);
        }

        return (new static())->withNativeInt($number);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $xdr->write($this->value, XDR::UINT);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return (new static())->withNativeInt($xdr->read(XDR::UINT));
    }

    /**
     * An alias for 'getValue'
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
     * @return UInt32
     */
    public function withNativeInt(int $value): UInt32
    {
        $clone = Copy::deep($this);
        $clone->value = $value;

        return $clone;
    }

    /**
     * Check if this number is equal to the given value.
     *
     * @param UInt32|int $that
     * @return bool
     */
    public function isEqualTo(UInt32|int $that): bool
    {
        if ($that instanceof UInt32) {
            $that = $that->toNativeInt();
        }

        return $this->toNativeInt() == $that;
    }
}
