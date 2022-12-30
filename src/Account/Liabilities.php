<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Account;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class Liabilities implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Int64 $buying;
    protected Int64 $selling;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->buying)) {
            throw new InvalidArgumentException('The liabilities are missing a buying value');
        }

        if (!isset($this->selling)) {
            throw new InvalidArgumentException('The liabilities are missing a selling value');
        }

        $xdr->write($this->buying)->write($this->selling);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $liabilities = new static();
        $liabilities->buying = $xdr->read(Int64::class);
        $liabilities->selling = $xdr->read(Int64::class);

        return $liabilities;
    }

    /**
     * Get the buying liability value.
     *
     * @return Int64
     */
    public function getBuying(): Int64
    {
        return $this->buying;
    }

    /**
     * Accept a buying liability value.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $buying
     * @return static
     */
    public function withBuying(Int64|ScaledAmount|int|string $buying): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->buying = Int64::normalize($buying);

        return $clone;
    }

    /**
     * Get the selling liability value.
     *
     * @return Int64
     */
    public function getSelling(): Int64
    {
        return $this->selling;
    }

    /**
     * Accept a selling liability value.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $selling
     * @return static
     */
    public function withSelling(Int64|ScaledAmount|int|string $selling): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->selling = Int64::normalize($selling);

        return $clone;
    }
}
