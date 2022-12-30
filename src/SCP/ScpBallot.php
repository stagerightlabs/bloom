<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Primitives\Value;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ScpBallot implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected UInt32 $counter;
    protected Value $value;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->counter)) {
            throw new InvalidArgumentException('The SCP ballot is missing a counter');
        }

        if (!isset($this->value)) {
            throw new InvalidArgumentException('The SCP ballot is missing a value');
        }

        $xdr->write($this->counter)->write($this->value);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $scpBallot = new static();
        $scpBallot->counter = $xdr->read(UInt32::class);
        $scpBallot->value = $xdr->read(Value::class);

        return $scpBallot;
    }

    /**
     * Get the counter.
     *
     * @return UInt32
     */
    public function getCounter(): UInt32
    {
        return $this->counter;
    }

    /**
     * Accept a counter.
     *
     * @param UInt32|int $counter
     * @return static
     */
    public function withCounter(UInt32|int $counter): static
    {
        if (is_int($counter)) {
            $counter = UInt32::of($counter);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->counter = Copy::deep($counter);

        return $clone;
    }

    /**
     * Get the value.
     *
     * @return Value
     */
    public function getValue(): Value
    {
        return $this->value;
    }

    /**
     * Accept a value.
     *
     * @param Value|string $value
     * @return static
     */
    public function withValue(Value|string $value): static
    {
        if (is_string($value)) {
            $value = Value::of($value);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->value = Copy::deep($value);

        return $clone;
    }
}
