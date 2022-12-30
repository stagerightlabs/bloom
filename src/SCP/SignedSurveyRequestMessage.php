<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\SCP;

use StageRightLabs\Bloom\Cryptography\Signature;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class SignedSurveyRequestMessage implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Signature $requestSignature;
    protected SurveyRequestMessage $request;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->requestSignature)) {
            throw new InvalidArgumentException('The signed survey request message is missing a signature');
        }

        if (!isset($this->request)) {
            throw new InvalidArgumentException('The signed survey request message is missing a request');
        }

        $xdr->write($this->requestSignature)->write($this->request);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $signedSurveyRequestMessage = new static();
        $signedSurveyRequestMessage->requestSignature = $xdr->read(Signature::class);
        $signedSurveyRequestMessage->request = $xdr->read(SurveyRequestMessage::class);

        return $signedSurveyRequestMessage;
    }

    /**
     * Get the signature.
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->requestSignature;
    }

    /**
     * Accept a signature.
     *
     * @param Signature $requestSignature
     * @return static
     */
    public function withSignature(Signature $requestSignature): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->requestSignature = Copy::deep($requestSignature);

        return $clone;
    }

    /**
     * Get the request.
     *
     * @return SurveyRequestMessage
     */
    public function getRequest(): SurveyRequestMessage
    {
        return $this->request;
    }

    /**
     * Accept a request.
     *
     * @param SurveyRequestMessage $request
     * @return static
     */
    public function withRequest(SurveyRequestMessage $request): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->request = Copy::deep($request);

        return $clone;
    }
}
