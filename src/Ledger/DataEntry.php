<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\DataValue;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class DataEntry implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected String64 $dataName;
    protected DataValue $dataValue;
    protected DataEntryExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The data entry is missing an account Id');
        }

        if (!isset($this->dataName)) {
            throw new InvalidArgumentException('The data entry is missing a data name');
        }

        if (!isset($this->dataValue)) {
            throw new InvalidArgumentException('The data entry is missing a data value');
        }

        if (!isset($this->ext)) {
            $this->ext = DataEntryExt::empty();
        }

        $xdr->write($this->accountId)
            ->write($this->dataName)
            ->write($this->dataValue)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $dataEntry = new static();
        $dataEntry->accountId = $xdr->read(AccountId::class);
        $dataEntry->dataName = $xdr->read(String64::class);
        $dataEntry->dataValue = $xdr->read(DataValue::class);
        $dataEntry->ext = $xdr->read(DataEntryExt::class);

        return $dataEntry;
    }

    /**
     * Get the account Id.
     *
     * @return AccountId
     */
    public function getAccountId(): AccountId
    {
        return $this->accountId;
    }

    /**
     * Accept an account Id.
     *
     * @param AccountId|Addressable|string $accountId
     * @return static
     */
    public function withAccountId(AccountId|Addressable|string $accountId): static
    {
        if (is_string($accountId)) {
            $accountId = AccountId::fromAddressable($accountId);
        } elseif ($accountId instanceof Addressable) {
            $accountId = AccountId::fromAddressable($accountId->getAddress());
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = Copy::deep($accountId);

        return $clone;
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
        if (is_string($dataName)) {
            $dataName = String64::of($dataName);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->dataName = Copy::deep($dataName);

        return $clone;
    }

    /**
     * Get the data value.
     *
     * @return DataValue
     */
    public function getDataValue(): DataValue
    {
        return $this->dataValue;
    }

    /**
     * Accept a data value.
     *
     * @param DataValue|string $dataValue
     * @return static
     */
    public function withDataValue(DataValue|string $dataValue): static
    {
        if (is_string($dataValue)) {
            $dataValue = DataValue::of($dataValue);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->dataValue = Copy::deep($dataValue);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return DataEntryExt
     */
    public function getExtension(): DataEntryExt
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param DataEntryExt $ext
     * @return static
     */
    public function withExtension(DataEntryExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
