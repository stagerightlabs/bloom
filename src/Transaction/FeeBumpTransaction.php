<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Envelope\TransactionV1Envelope;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class FeeBumpTransaction implements XdrStruct
{
    /**
     * Properties
     */
    protected AccountId $feeSource;
    protected Int64 $fee;
    protected FeeBumpTransactionInnerTx $innerTransaction;
    protected FeeBumpTransactionExt $ext;

    /**
     * Create a new Fee Bump transaction from a regular transaction envelope.
     *
     * @param TransactionEnvelope $envelope
     * @param Int64|int $fee
     * @param AccountId|Addressable|string $feeSource
     * @return FeeBumpTransaction
     * @throws InvalidArgumentException
     */
    public static function for(TransactionEnvelope $envelope, Int64|int $fee, AccountId|Addressable|string $feeSource)
    {
        // Ensure the provided fee is at least as much as the existing inner transaction fee?

        // Extract TransactionV1Envelope from Transaction envelope
        $innerTransaction = $envelope->unwrap();
        if (!$innerTransaction instanceof TransactionV1Envelope) {
            throw new InvalidArgumentException('At the moment fee bump transactions can only be created for TransactionV1Envelopes');
        }

        $feeBumpTransaction = new FeeBumpTransaction();
        $feeBumpTransaction->innerTransaction = (new FeeBumpTransactionInnerTx())
            ->wrapTransactionV1Envelope($innerTransaction);
        $feeBumpTransaction->fee = Int64::normalize($fee);
        $feeBumpTransaction->feeSource = AccountId::fromAddressable($feeSource);
        $feeBumpTransaction->ext = FeeBumpTransactionExt::empty();

        return $feeBumpTransaction;
    }

    /**
     * Encode the transaction as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->feeSource)) {
            throw new InvalidArgumentException('The fee bump transaction is missing a fee source');
        }

        if (!isset($this->fee)) {
            throw new InvalidArgumentException('The fee bump transaction is missing a fee');
        }

        if (!isset($this->innerTransaction)) {
            throw new InvalidArgumentException('The fee bump transaction is missing an inner transaction.');
        }

        if (!isset($this->ext)) {
            $this->ext = FeeBumpTransactionExt::empty();
        }

        $xdr->write($this->feeSource)
            ->write($this->fee)
            ->write($this->innerTransaction)
            ->write($this->ext);
    }

    /**
     * Read the transaction from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $feeBumpTransaction = new static();
        $feeBumpTransaction->feeSource = $xdr->read(AccountId::class);
        $feeBumpTransaction->fee = $xdr->read(Int64::class);
        $feeBumpTransaction->innerTransaction = $xdr->read(FeeBumpTransactionInnerTx::class);
        $feeBumpTransaction->ext = $xdr->read(FeeBumpTransactionExt::class);

        return $feeBumpTransaction;
    }

    /**
     * Get the fee source.
     *
     * @return AccountId
     */
    public function getFeeSource(): AccountId
    {
        return $this->feeSource;
    }

    /**
     * Set the fee source.
     *
     * @param AccountId $feeSource
     *
     * @return static
     */
    public function withFeeSource(AccountId $feeSource): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->feeSource = Copy::deep($feeSource);

        return $clone;
    }

    /**
     * Get the fee.
     *
     * @return Int64
     */
    public function getFee(): Int64
    {
        return $this->fee;
    }

    /**
     * Set the fee.
     *
     * A string will be read as a scaled amount; an integer as a descaled value.
     *
     * @param Int64|ScaledAmount|int|string $fee
     * @return static
     */
    public function withFee(Int64|ScaledAmount|int|string $fee): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->fee = Int64::normalize($fee);

        return $clone;
    }

    /**
     * Get the inner transaction.
     *
     * @return FeeBumpTransactionInnerTx
     */
    public function getInnerTransaction(): FeeBumpTransactionInnerTx
    {
        return $this->innerTransaction;
    }

    /**
     * Set the inner transaction.
     *
     * @param FeeBumpTransactionInnerTx $innerTransaction
     *
     * @return static
     */
    public function withInnerTransaction(FeeBumpTransactionInnerTx $innerTransaction): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->innerTransaction = Copy::deep($innerTransaction);

        return $clone;
    }

    /**
     * Get the ext.
     *
     * @return FeeBumpTransactionExt
     */
    public function getExtension(): FeeBumpTransactionExt
    {
        return $this->ext;
    }

    /**
     * Set the ext.
     *
     * @param FeeBumpTransactionExt $ext
     *
     * @return static
     */
    public function withExtension(FeeBumpTransactionExt $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
