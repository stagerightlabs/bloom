<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\String64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerKeyData implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $accountId;
    protected String64 $dataName;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->accountId)) {
            throw new InvalidArgumentException('The ledger key data is missing an account Id');
        }

        if (!isset($this->dataName)) {
            throw new InvalidArgumentException('The ledger key data is missing a data name');
        }

        $xdr->write($this->accountId)->write($this->dataName);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $ledgerKeyData = new static();
        $ledgerKeyData->accountId = $xdr->read(AccountId::class);
        $ledgerKeyData->dataName = $xdr->read(String64::class);

        return $ledgerKeyData;
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
        /** @var static */
        $clone = Copy::deep($this);
        $clone->accountId = AccountId::fromAddressable($accountId);

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
     * Accept a data nam.
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
}
