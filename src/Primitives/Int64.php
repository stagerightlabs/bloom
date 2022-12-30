<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\Number;
use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class Int64 extends Integer implements XdrTypedef
{
    /**
     * Write this value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $pad = $this->bigInteger->getSign() === 1 ? chr(0) : chr(255);
        $pad = $this->bigInteger->isEqualTo(0) ? chr(0) : $pad;
        $bytes = XDR::pad($this->toBytes(), 8, $pad, STR_PAD_LEFT);
        $xdr->write($bytes, XDR::OPAQUE_FIXED, 8);
    }

    /**
     * Read this value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        return self::fromBytes($xdr->read(XDR::OPAQUE_FIXED, length: 8));
    }

    /**
     * Convert this value to a scaled amount.
     *
     * @return ScaledAmount
     */
    public function scale(): ScaledAmount
    {
        return ScaledAmount::of($this);
    }

    /**
     * Normalize a variety of input types into an Int64 instance.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     * If given an Int64 it will return a new clone instance.
     *
     * @param Int64|ScaledAmount|int|string $value
     * @return Int64
     */
    public static function normalize(Int64|ScaledAmount|int|string $value): Int64
    {
        if (is_int($value)) {
            return Int64::of($value);
        }

        if (is_string($value)) {
            return ScaledAmount::of($value)->descale();
        }

        if ($value instanceof ScaledAmount) {
            return $value->descale();
        }

        return Copy::deep($value);
    }

    /**
     * Create a new instance representing the largest possible Int64 value.
     *
     * @return static
     */
    public static function max(): static
    {
        return self::of(Number::MAX_INT64);
    }
}
