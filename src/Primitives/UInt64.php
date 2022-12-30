<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Primitives;

use StageRightLabs\PhpXdr\Interfaces\XdrTypedef;
use StageRightLabs\PhpXdr\XDR;

class UInt64 extends Integer implements XdrTypedef
{
    /**
     * Write this value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        $bytes = XDR::pad($this->toBytes(), 8, chr(0), STR_PAD_LEFT);
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
}
