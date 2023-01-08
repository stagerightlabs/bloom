<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Account\Signatory;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Exception\InvalidKeyException;
use StageRightLabs\Bloom\Horizon\Error;
use StageRightLabs\Bloom\Horizon\TransactionResource;
use StageRightLabs\Bloom\Service;
use StageRightLabs\Bloom\Transaction\FeeBumpTransaction;
use StageRightLabs\Bloom\Transaction\Transaction;
use StageRightLabs\Bloom\Transaction\TransactionV0;
use StageRightLabs\PhpXdr\XDR;

final class EnvelopeService extends Service
{
    /**
     * Wrap a transaction in an envelope to prepare for signing.
     *
     * @param Transaction|TransactionV0|FeeBumpTransaction $transaction
     * @return TransactionEnvelope
     */
    public function enclose(Transaction|TransactionV0|FeeBumpTransaction $transaction): TransactionEnvelope
    {
        return TransactionEnvelope::enclose($transaction);
    }

    /**
     * Add a new signature to an envelope's signature list.
     *
     * @param TransactionEnvelope $envelope
     * @param Signatory $signer
     * @throws InvalidArgumentException
     * @throws InvalidKeyException
     * @return TransactionEnvelope
     */
    public function sign(TransactionEnvelope $envelope, Signatory $signer): TransactionEnvelope
    {
        $passphrase = $this->bloom->config->getNetworkPassphrase();
        if (!$message = $envelope->getHash($passphrase)) {
            throw new InvalidArgumentException('The transaction envelope does not have a valid hash');
        }
        $signature = $signer->signDecorated($message->toNativeString());

        return $envelope->addSignature($signature);
    }

    /**
     * Submit a signed transaction to Horizon.
     *
     * @param TransactionEnvelope $envelope
     * @return TransactionResource|Error
     */
    public function post(TransactionEnvelope $envelope): TransactionResource|Error
    {
        $url = $this->bloom->horizon->url('transactions');
        $response = $this->bloom->horizon->post($url, [
            'tx' => XDR::fresh()->write($envelope)->toBase64()
        ]);

        return (!$response instanceof Error)
            ? TransactionResource::fromResponse($response)
            : $response;
    }

    /**
     * Convert a transaction envelope to a Base 64 XDR string.
     *
     * @param TransactionEnvelope $envelop
     * @return string
     */
    public function toXdr(TransactionEnvelope $envelop): string
    {
        return XDR::fresh()->write($envelop)->toBase64();
    }

    /**
     * Decode a transaction envelope from a base 64 XDR string.
     *
     * @return TransactionEnvelope
     * @throws InvalidArgumentException
     */
    public function fromXdr(string $xdr): TransactionEnvelope
    {
        try {
            $envelope = XDR::fromBase64($xdr)->read(TransactionEnvelope::class);
        } catch (\Throwable $th) {
            throw new InvalidArgumentException('Invalid transaction envelope XDR');
        }

        return $envelope;
    }
}
