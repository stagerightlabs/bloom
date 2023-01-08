<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Operation\OperationMeta;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\ScaledAmount;
use StageRightLabs\Bloom\Transaction\TransactionMeta;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\Bloom\Utility\Number;
use StageRightLabs\PhpXdr\XDR;

/**
 * @see https://developers.stellar.org/api/resources/transactions/object/
 */
class TransactionResource extends Resource
{
    /**
     * Return the 'self' link.
     *
     * @return string|null
     */
    public function getSelfLink(): ?string
    {
        return $this->getLink('self');
    }

    /**
     * Return the 'account' link.
     *
     * @return string|null
     */
    public function getAccountLink(): ?string
    {
        return $this->getLink('account');
    }

    /**
     * Return the 'ledger' link.
     *
     * @return string|null
     */
    public function getLedgerLink(): ?string
    {
        return $this->getLink('ledger');
    }

    /**
     * Return the 'operations' link.
     *
     * @return string|null
     */
    public function getOperationsLink(): ?string
    {
        return $this->getLink('operations');
    }

    /**
     * Return the 'effects' link.
     *
     * @return string|null
     */
    public function getEffectsLink(): ?string
    {
        return $this->getLink('effects');
    }

    /**
     * Return the 'precedes' link.
     *
     * @return string|null
     */
    public function getPrecedesLink(): ?string
    {
        return $this->getLink('precedes');
    }

    /**
     * Return the 'succeeds' link.
     *
     * @return string|null
     */
    public function getSucceedsLink(): ?string
    {
        return $this->getLink('succeeds');
    }

    /**
     * A unique identifier for this transaction.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->payload->getString('id');
    }

    /**
     * A cursor value for use in pagination.
     *
     * @return string|null
     */
    public function getPagingToken(): ?string
    {
        return $this->payload->getString('paging_token');
    }

    /**
     * Indicates if this transaction was successful or not.
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return $this->payload->getBoolean('successful') ?? false;
    }

    /**
     * A hex-encoded SHA-256 hash of this transaction’s XDR-encoded form.
     *
     * @return string|null
     */
    public function getHash(): ?string
    {
        return $this->payload->getString('hash');
    }

    /**
     * The sequence number of the ledger that this transaction was included in.
     *
     * @return string|null
     */
    public function getLedgerSequence(): ?string
    {
        return $this->payload->getString('ledger');
    }

    /**
     * The date this transaction was created.
     *
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        if ($epoch = $this->payload->getString('created_at')) {
            return new \DateTime($epoch);
        }

        return null;
    }

    /**
     * The address of the account that originates the transaction.
     *
     * @return string|null
     */
    public function getSourceAccountAddress(): ?string
    {
        return $this->payload->getString('source_account');
    }

    /**
     * The source account’s sequence number that this transaction consumed.
     *
     * @return string|null
     */
    public function getSourceAccountSequence(): ?string
    {
        return $this->payload->getString('source_account_sequence');
    }

    /**
     * The fee paid by the source account to apply this transaction to the ledger.
     *
     * Automatically 'de-scaled' from stroops to lumens.
     *
     * @return ScaledAmount|null
     */
    public function getFeeCharged(): ?ScaledAmount
    {
        if ($fee = $this->payload->getInteger('fee_charged')) {
            return Int64::of($fee)->scale();
        }

        return null;
    }

    /**
     * The maximum fee that the source account was willing to pay.
     *
     * Automatically 'de-scaled' from stroops to lumens.
     *
     * @return ScaledAmount|null
     */
    public function getMaxFee(): ?ScaledAmount
    {
        if ($fee = $this->payload->getInteger('max_fee')) {
            return Int64::of($fee)->scale();
        }

        return null;
    }

    /**
     * The number of operations contained within this transaction.
     *
     * @return int|null
     */
    public function getOperationCount(): ?int
    {
        return $this->payload->getInteger('operation_count');
    }

    /**
     * A base64 encoded string of the raw TransactionEnvelope XDR struct
     * for this transaction.
     *
     * @return string|null
     */
    public function getEnvelopeXdr(): ?string
    {
        return $this->payload->getString('envelope_xdr');
    }

    /**
     * The Transaction Envelope for this transaction, decoded from XDR.
     *
     * @return TransactionEnvelope|null
     */
    public function getEnvelope(): ?TransactionEnvelope
    {
        try {
            return XDR::fromBase64(strval($this->getEnvelopeXdr()))
                ->read(TransactionEnvelope::class);
        } catch (\Throwable $th) {
            // for now we will do nothing and just return null
        }

        return null;
    }

    /**
     * A base64 encoded string of the raw TransactionResult XDR struct
     * for this transaction.
     *
     * @return string|null
     */
    public function getResultXdr(): ?string
    {
        return $this->payload->getString('result_xdr');
    }

    /**
     * The transaction result returned by the network, as decoded from the XDR.
     *
     * @return TransactionResult|null
     */
    public function getResult(): ?TransactionResult
    {
        if ($this->getResultXdr()) {
            return XDR::fromBase64($this->getResultXdr())
                ->read(TransactionResult::class);
        }

        return null;
    }

    /**
     * A base64 encoded string of the raw TransactionMeta XDR struct
     * for this transaction
     *
     * @return string|null
     */
    public function getResultMetaXdr(): ?string
    {
        return $this->payload->getString('result_meta_xdr');
    }

    /**
     * The transaction result meta information returned by the network,
     * as decoded from the XDR.
     *
     * @return TransactionMeta|null
     */
    public function getResultMeta(): ?TransactionMeta
    {
        if ($this->getResultMetaXdr()) {
            return XDR::fromBase64($this->getResultMetaXdr())
                ->read(TransactionMeta::class);
        }

        return null;
    }

    /**
     * A base64 encoded string of the raw LedgerEntryChanges XDR struct
     * produced by taking fees for this transaction.
     *
     * @return string|null
     */
    public function getFeeMetaXdr(): ?string
    {
        return $this->payload->getString('fee_meta_xdr');
    }

    /**
     * The transaction result meta information returned by the network,
     * as decoded from the XDR.
     *
     * @return OperationMeta|null
     */
    public function getFeeMeta(): ?OperationMeta
    {
        if ($this->getFeeMetaXdr()) {
            return XDR::fromBase64($this->getFeeMetaXdr())
                ->read(OperationMeta::class);
        }

        return null;
    }

    /**
     * The optional memo attached to a transaction.
     *
     * @return string|null
     */
    public function getMemo(): ?string
    {
        return $this->payload->getString('memo');
    }

    /**
     * The type of memo. Potential values include:
     *  - MEMO_TEXT
     *  - MEMO_ID
     *  - MEMO_HASH
     *  - MEMO_RETURN
     *
     * @return string|null
     */
    public function getMemoType(): ?string
    {
        return $this->payload->getString('memo_type');
    }

    /**
     * An array of signatures used to sign this transaction.
     *
     * @return array<string>
     */
    public function getSignatures(): array
    {
        return $this->payload->getArray('signatures') ?? [];
    }

    /**
     * A set of transaction preconditions affecting the validity of the transaction.
     *
     * @return array<int|string, mixed>
     */
    public function getPreconditions(): array
    {
        return $this->payload->getArray('preconditions') ?? [];
    }

    /**
     * The lower bound of the time range in which this transaction is valid.
     *
     * @return \DateTime|null
     */
    public function getMinTimeCondition(): ?\DateTime
    {
        $epoch = $this->payload->getString('preconditions.time_bounds.min_time');

        if ($epoch === '0' || is_null($epoch)) {
            return null;
        }

        return new \DateTime('@' . $epoch);
    }

    /**
     * The upper bound of the time range in which this transaction is valid.
     *
     * @return \DateTime|null
     */
    public function getMaxTimeCondition(): ?\DateTime
    {
        $epoch = $this->payload->getString('preconditions.time_bounds.max_time');

        if ($epoch === '0' || is_null($epoch)) {
            return null;
        }

        return new \DateTime('@' . $epoch);
    }

    /**
     * The lower bound of the ledger range for which this transaction is valid.
     *
     * @return int|null
     */
    public function getMinLedgerCondition(): ?int
    {
        return $this->payload->getInteger('preconditions.ledger_bounds.min_ledger');
    }

    /**
     * The upper bound of the ledger range for which this transaction is valid.
     *
     * @return int|null
     */
    public function getMaxLedgerCondition(): ?int
    {
        return $this->payload->getInteger('preconditions.ledger_bounds.max_ledger');
    }

    /**
     * The lowest source account sequence number for which this transaction is valid.
     *
     * @return string|null
     */
    public function getMinAccountSequenceCondition(): ?string
    {
        return $this->payload->getString('preconditions.min_account_sequence');
    }

    /**
     * The minimum duration of time (in seconds) that must have passed since
     * the source account's sequence number changed.
     *
     * @return Int64|null
     */
    public function getMinAccountSequenceAgeCondition(): ?Int64
    {
        if ($seconds = $this->payload->getString('preconditions.min_account_sequence_age')) {
            return Int64::of($seconds);
        }

        return null;
    }

    /**
     * The number of ledgers that must have closed since the source account's
     * sequence number changed for the transaction to be valid.
     *
     * @return int|null
     */
    public function getMinAccountSequenceLedgerGapCondition(): ?int
    {
        return $this->payload->getInteger('preconditions.min_account_sequence_ledger_gap');
    }

    /**
     * The list of up to two additional signers that must have corresponding
     * signatures for this transaction to be valid.
     *
     * @return array<string>|null
     */
    public function getExtraSignersCondition(): ?array
    {
        return $this->payload->getArray('preconditions.extra_signers');
    }
}
