<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class BumpSequenceOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected SequenceNumber $bumpTo;

    /**
     * Create a new bump-sequence operation.
     *
     * @param SequenceNumber|Int64|integer $bumpTo
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        SequenceNumber|Int64|int $bumpTo,
        Addressable|string $source = null
    ): Operation {
        $bumpSequenceOp = (new static())
            ->withBumpTo($bumpTo);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::BUMP_SEQUENCE, $bumpSequenceOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->bumpTo) && $this->bumpTo instanceof SequenceNumber);
    }

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->bumpTo)) {
            throw new InvalidArgumentException('The bump sequence operation is missing a target sequence number');
        }

        $xdr->write($this->bumpTo);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $bumpSequenceOp = new static();
        $bumpSequenceOp->bumpTo = $xdr->read(SequenceNumber::class);

        return $bumpSequenceOp;
    }

    /**
     * Get the target sequence number.
     *
     * @return SequenceNumber
     */
    public function getBumpTo(): SequenceNumber
    {
        return $this->bumpTo;
    }

    /**
     * Accept a target sequence number.
     *
     * @param SequenceNumber|Int64|int $bumpTo
     * @return static
     */
    public function withBumpTo(SequenceNumber|Int64|int $bumpTo): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->bumpTo = $bumpTo instanceof SequenceNumber
            ? Copy::deep($bumpTo)
            : SequenceNumber::of($bumpTo);

        return $clone;
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_MEDIUM;
    }
}
