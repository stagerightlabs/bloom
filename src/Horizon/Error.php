<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Envelope\TransactionEnvelope;
use StageRightLabs\Bloom\Exception\UnexpectedValueException;
use StageRightLabs\Bloom\Transaction\TransactionResult;
use StageRightLabs\Bloom\Utility\Json;
use StageRightLabs\PhpXdr\XDR;

/**
 * @see https://github.com/stellar/new-docs/blob/master/content/api/errors/response.mdx
 */
final class Error
{
    /**
     * Properties
     */
    protected Json $payload;
    protected ?Response $response;
    public const TX_SUCCESS = 'tx_success';
    public const TX_SUCCESS_MESSAGE = 'The transaction succeeded.';
    public const TX_FAILED = 'tx_failed';
    public const TX_TOO_EARLY = 'tx_too_early';
    public const TX_TOO_LATE = 'tx_too_late';
    public const TX_MISSING_OPERATION = 'tx_missing_operation';
    public const TX_BAD_SEQ = 'tx_bad_seq';
    public const TX_BAD_AUTH = 'tx_bad_auth';
    public const TX_INSUFFICIENT_BALANCE = 'tx_insufficient_balance';
    public const TX_NO_SOURCE_ACCOUNT = ' tx_no_source_account';
    public const TX_INSUFFICIENT_FEE = 'tx_insufficient_fee';
    public const TX_BAD_AUTH_EXTRA = 'tx_bad_auth_extra';
    public const TX_INTERNAL_ERROR = 'tx_internal_error';

    /**
     * Instantiate a new class instance.
     *
     * @param array<string, mixed>|string $payload
     * @param Response|null $response
     */
    public function __construct(array|string $payload = '', Response $response = null)
    {
        $this->payload = is_array($payload)
            ? Json::fromArray($payload)
            : Json::of($payload);
        $this->response = $response;
    }

    /**
     * Create a new class instance from a Horizon response.
     *
     * @param Response $response
     * @throws UnexpectedValueException
     * @return static
     */
    public static function fromResponse(Response $response): static
    {
        return new static(strval($response->getBody()), $response);
    }

    /**
     * Return the original server response.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Return the decoded json payload.
     *
     * @return Json
     */
    public function getPayload(): Json
    {
        return $this->payload;
    }

    /**
     * Return the error code type. This is a link to relevant documentation.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->payload->getString('type');
    }

    /**
     * Return a short title describing the status code.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->payload->getString('title');
    }

    /**
     * Return the status code.
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->payload->getInteger('status');
    }

    /**
     * Return a description of the error.
     *
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->payload->getString('detail');
    }

    /**
     * Return an extra information included with the error.
     *
     * If the status code is `Transaction Failed` this extras field contains
     * additional information about why a transaction failed.
     *
     * @return array<int|string, mixed>
     */
    public function getExtras(): array
    {
        return $this->payload->getArray('extras') ?? [];
    }

    /**
     * A base64-encoded representation of the TransactionEnvelope XDR whose
     * failure triggered this response.
     *
     * @return string|null
     */
    public function getEnvelopeXdr(): ?string
    {
        return $this->payload->getString('extras.envelope_xdr');
    }

    /**
     * The transaction envelope that triggered this error, as decoded
     * from the XDR in the error response.
     *
     * @return TransactionEnvelope|null
     */
    public function getEnvelope(): ?TransactionEnvelope
    {
        if ($this->getEnvelopeXdr()) {
            return XDR::fromBase64($this->getEnvelopeXdr())
                ->read(TransactionEnvelope::class);
        }

        return null;
    }

    /**
     * A base64-encoded representation of the TransactionResult XDR returned by
     * the network in the error response.
     *
     * @return string|null
     */
    public function getResultXdr(): ?string
    {
        return $this->payload->getString('extras.result_xdr');
    }

    /**
     * The transaction result returned by the network, as decoded from the
     * XDR in the error response.
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
     * The transaction Result Code returned by Stellar Core, which can be used
     * to look up more information about an error in the docs.
     *
     * @see https://developers.stellar.org/api/errors/result-codes/transactions/
     * @return string|null
     */
    public function getTransactionResultCode(): ?string
    {
        return $this->payload->getString('extras.result_codes.transaction');
    }

    /**
     * An array of operation Result Codes returned by Stellar Core, which can
     * be used to look up more information about an error in the docs.
     *
     * @see https://developers.stellar.org/api/errors/result-codes/operations/
     * @return array<string>|null
     */
    public function getOperationResultCodes(): ?array
    {
        return $this->payload->getArray('extras.result_codes.operations');
    }

    /**
     * A developer friendly error message generated from the content of the response.
     *
     * @return string
     */
    public function getMessage(): string
    {
        $message = 'something went wrong...';
        $transactionResultCode = strval($this->getTransactionResultCode());

        if (array_key_exists($transactionResultCode, self::TRANSACTION_ERROR_CODES)) {
            $message =
                "[{$transactionResultCode}] "
                . self::TRANSACTION_ERROR_CODES[$transactionResultCode];
        }

        return 'Error:' . ': ' . $message;
    }

    /**
     * Transaction error codes and messages.
     */
    protected const TRANSACTION_ERROR_CODES = [
        self::TX_SUCCESS              => 'The transaction succeeded.',
        self::TX_FAILED               => 'One of the operations failed (none were applied).',
        self::TX_TOO_EARLY            => 'The ledger closeTime was before the minTime.',
        self::TX_TOO_LATE             => 'The ledger closeTime was after the maxTime.',
        self::TX_MISSING_OPERATION    => 'No operation was specified',
        self::TX_BAD_SEQ              => 'Sequence number does not match source account.',
        self::TX_BAD_AUTH             => 'Too few valid signatures or wrong network',
        self::TX_INSUFFICIENT_BALANCE => 'Fee would bring account below reserve.',
        self::TX_NO_SOURCE_ACCOUNT    => 'Source account not found',
        self::TX_INSUFFICIENT_FEE     => 'Fee is too small',
        self::TX_BAD_AUTH_EXTRA       => 'Unused signatures attached to transaction',
        self::TX_INTERNAL_ERROR       => 'An unknown error occurred.',
    ];

    /**
     * A sanity check for the API consumer: Did the request fail?
     *
     * @return bool
     */
    public function requestFailed(): bool
    {
        return true;
    }
}
