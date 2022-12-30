<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerEntryChanges;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TransactionResultMeta implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected TransactionResultPair $result;
    protected LedgerEntryChanges $feeProcessing;
    protected TransactionMeta $txApplyProcessing;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->result)) {
            throw new InvalidArgumentException('The transaction result meta is missing a result');
        }

        if (!isset($this->feeProcessing)) {
            throw new InvalidArgumentException('The transaction result meta is missing fee processing information');
        }

        if (!isset($this->txApplyProcessing)) {
            throw new InvalidArgumentException('The transaction result meta is missing transaction processing information');
        }

        $xdr->write($this->result)
            ->write($this->feeProcessing)
            ->write($this->txApplyProcessing);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $transactionResultMeta = new static();
        $transactionResultMeta->result = $xdr->read(TransactionResultPair::class);
        $transactionResultMeta->feeProcessing = $xdr->read(LedgerEntryChanges::class);
        $transactionResultMeta->txApplyProcessing = $xdr->read(TransactionMeta::class);

        return $transactionResultMeta;
    }

    /**
     * Get the result.
     *
     * @return TransactionResultPair
     */
    public function getResult(): TransactionResultPair
    {
        return $this->result;
    }

    /**
     * Accept a result.
     *
     * @param TransactionResultPair $result
     * @return static
     */
    public function withResult(TransactionResultPair $result): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->result = Copy::deep($result);

        return $clone;
    }

    /**
     * Get the fee processing information.
     *
     * @return LedgerEntryChanges
     */
    public function getFeeProcessing(): LedgerEntryChanges
    {
        return $this->feeProcessing;
    }

    /**
     * Accept fee processing information.
     *
     * @param LedgerEntryChanges $feeProcessing
     * @return static
     */
    public function withFeeProcessing(LedgerEntryChanges $feeProcessing): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->feeProcessing = Copy::deep($feeProcessing);

        return $clone;
    }

    /**
     * Get the transaction processing information.
     *
     * @return TransactionMeta
     */
    public function getTxApplyProcessing(): TransactionMeta
    {
        return $this->txApplyProcessing;
    }

    /**
     * Accept transaction processing information.
     *
     * @param TransactionMeta $txApplyProcessing
     * @return static
     */
    public function withTxApplyProcessing(TransactionMeta $txApplyProcessing): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->txApplyProcessing = Copy::deep($txApplyProcessing);

        return $clone;
    }
}
