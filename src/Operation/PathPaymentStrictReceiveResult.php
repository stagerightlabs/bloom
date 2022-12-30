<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class PathPaymentStrictReceiveResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return PathPaymentStrictReceiveResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS            => PathPaymentStrictReceiveResultSuccess::class,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED          => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED        => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST       => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST           => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL          => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER          => Asset::class,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS     => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF => XDR::VOID,
            PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX       => XDR::VOID,
        ];
    }

    /**
     * Create a new instance that wraps a PathPaymentStrictReceiveResultSuccess object.
     *
     * @param PathPaymentStrictReceiveResultSuccess $pathPaymentStrictReceiveResultSuccess
     * @return static
     */
    public static function wrapPathPaymentStrictReceiveResultSuccess(PathPaymentStrictReceiveResultSuccess $pathPaymentStrictReceiveResultSuccess): static
    {
        $pathPaymentStrictReceiveResult = new static();
        $pathPaymentStrictReceiveResult->discriminator = PathPaymentStrictReceiveResultCode::success();
        $pathPaymentStrictReceiveResult->value = $pathPaymentStrictReceiveResultSuccess;

        return $pathPaymentStrictReceiveResult;
    }

    /**
     * Create a new instance that wraps an asset object, representing a 'no
     * issuer' error.
     *
     * @param Asset $asset
     * @return static
     */
    public static function wrapNoIssuerAsset(Asset $asset): static
    {
        $pathPaymentStrictReceiveResult = new static();
        $pathPaymentStrictReceiveResult->discriminator = PathPaymentStrictReceiveResultCode::noIssuer();
        $pathPaymentStrictReceiveResult->value = $asset;

        return $pathPaymentStrictReceiveResult;
    }

    /**
     * Unwrap the path payment strict receive result.
     *
     * @return PathPaymentStrictReceiveResultSuccess|Asset|null
     */
    public function unwrap(): PathPaymentStrictReceiveResultSuccess|Asset|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictReceiveResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param PathPaymentStrictReceiveResultCode $code
     * @param PathPaymentStrictReceiveResult|Asset|null $value
     * @return static
     */
    public static function simulate(PathPaymentStrictReceiveResultCode $code, PathPaymentStrictReceiveResult|Asset $value = null): static
    {
        $pathPaymentStrictReceiveResult = new static();
        $pathPaymentStrictReceiveResult->discriminator = $code;
        $pathPaymentStrictReceiveResult->value = $value;

        return $pathPaymentStrictReceiveResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof PathPaymentStrictReceiveResultCode
            && $this->discriminator->getType() == PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS;
    }

    /**
     * Was the operation not successful?
     *
     * @return bool
     */
    public function wasNotSuccessful(): bool
    {
        return !$this->wasSuccessful();
    }

    /**
     * Return an error message that describes the problem if there was one.
     *
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictReceiveResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-receive
     * @var array<string, string>
     */
    protected $messages = [
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SUCCESS            => "The operation was successful.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_MALFORMED          => "The input to this path payment is invalid.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_UNDERFUNDED        => "The source account (sender) does not have enough funds to send and still satisfy its selling liabilities. Note that if sending XLM then the sender must additionally maintain its minimum XLM reserve.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NO_TRUST       => "The source account does not trust the issuer of the asset it is trying to send.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_SRC_NOT_AUTHORIZED => "The source account is not authorized to send this payment.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_DESTINATION     => "The destination account does not exist.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_TRUST           => "The destination account does not trust the issuer of the asset being sent.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NOT_AUTHORIZED     => "The destination account is not authorized by the asset's issuer to hold the asset.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_LINE_FULL          => "The destination account does not have sufficient limits to receive destination amount and still satisfy its buying liabilities.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_NO_ISSUER          => "One asset is missing an issuer",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_TOO_FEW_OFFERS     => "There is no path of offers connecting the send asset and destination asset. Stellar only considers paths of length 5 or shorter.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OFFER_CROSSES_SELF => "The payment would cross one of its own offers.",
        PathPaymentStrictReceiveResultCode::PATH_PAYMENT_STRICT_RECEIVE_OVER_SENDMAX       => "The paths that could send destination amount of destination asset would exceed send max.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictReceiveResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
