<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\OptionalDataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ManageDataOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected String64 $dataName;
    protected OptionalDataValue $dataValue;

    /**
     * Create a new manage-data operation.
     *
     * @param String64|string $name
     * @param DataValue|string|null $value
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        String64|string $name,
        DataValue|string $value = null,
        Addressable|string $source = null
    ): Operation {
        $manageDataOp = (new static())
            ->withDataName($name)
            ->withDataValue($value);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::MANAGE_DATA, $manageDataOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->dataName) && $this->dataName instanceof String64);
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
        if (!isset($this->dataName)) {
            throw new InvalidArgumentException('The manage data operation is missing a data name');
        }

        if (!isset($this->dataValue)) {
            $this->dataValue = OptionalDataValue::none();
        }

        $xdr->write($this->dataName)->write($this->dataValue);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $manageDataOp = new static();
        $manageDataOp->dataName = $xdr->read(String64::class);
        $manageDataOp->dataValue = $xdr->read(OptionalDataValue::class);

        return $manageDataOp;
    }

    /**
     * Get the data name.
     *
     * @return String64
     */
    public function getDataName(): String64
    {
        return $this->dataName;
    }

    /**
     * Accept a data name.
     *
     * @param String64|string $dataName
     * @return static
     */
    public function withDataName(String64|string $dataName): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->dataName = is_string($dataName)
            ? String64::of($dataName)
            : Copy::deep($dataName);

        return $clone;
    }

    /**
     * Get the data value.
     *
     * @return DataValue|null
     */
    public function getDataValue(): ?DataValue
    {
        return $this->dataValue->hasValue()
            ? $this->dataValue->unwrap()
            : null;
    }

    /**
     * Accept a data value.
     *
     * @param OptionalDataValue|DataValue|string|null $dataValue
     * @return static
     */
    public function withDataValue(OptionalDataValue|DataValue|string $dataValue = null): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->dataValue = is_null($dataValue)
            ? OptionalDataValue::none()
            : OptionalDataValue::some($dataValue);

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
