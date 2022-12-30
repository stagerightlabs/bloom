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

final class SignedSurveyResponseMessage implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Signature $responseSignature;
    protected SurveyResponseMessage $response;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->responseSignature)) {
            throw new InvalidArgumentException('The signed survey response message is missing a signature');
        }

        if (!isset($this->response)) {
            throw new InvalidArgumentException('The signed survey response message is missing a response');
        }

        $xdr->write($this->responseSignature)->write($this->response);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $signedSurveyResponseMessage = new static();
        $signedSurveyResponseMessage->responseSignature = $xdr->read(Signature::class);
        $signedSurveyResponseMessage->response = $xdr->read(SurveyResponseMessage::class);

        return $signedSurveyResponseMessage;
    }

    /**
     * Get the signature.
     *
     * @return Signature
     */
    public function getSignature(): Signature
    {
        return $this->responseSignature;
    }

    /**
     * Accept a signature.
     *
     * @param Signature $responseSignature
     * @return static
     */
    public function withSignature(Signature $responseSignature): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->responseSignature = Copy::deep($responseSignature);

        return $clone;
    }

    /**
     * Get the response.
     *
     * @return SurveyResponseMessage
     */
    public function getResponse(): SurveyResponseMessage
    {
        return $this->response;
    }

    /**
     * Accept a response.
     *
     * @param SurveyResponseMessage $response
     * @return static
     */
    public function withResponse(SurveyResponseMessage $response): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->response = Copy::deep($response);

        return $clone;
    }
}
