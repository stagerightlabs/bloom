<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class PathPaymentStrictSendResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return PathPaymentStrictSendResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS            => PathPaymentStrictSendResultSuccess::class,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED          => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED        => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST       => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_TRUST           => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_LINE_FULL          => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_ISSUER          => Asset::class,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS     => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF   => XDR::VOID,
            PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN      => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping a PathPaymentStrictSendResultSuccess instance.
     *
     * @param PathPaymentStrictSendResultSuccess $pathPaymentStrictSendResultSuccess
     * @return static
     */
    public static function wrapPathPaymentStrictSendResultSuccess(PathPaymentStrictSendResultSuccess $pathPaymentStrictSendResultSuccess): static
    {
        $pathPaymentStrictSendResult = new static();
        $pathPaymentStrictSendResult->discriminator = PathPaymentStrictSendResultCode::success();
        $pathPaymentStrictSendResult->value = $pathPaymentStrictSendResultSuccess;

        return $pathPaymentStrictSendResult;
    }

    /**
     * Create a new instance by wrapping an asset.
     *
     * @param Asset $asset
     * @return static
     */
    public static function wrapAsset(Asset $asset): static
    {
        $pathPaymentStrictSendResult = new static();
        $pathPaymentStrictSendResult->discriminator = PathPaymentStrictSendResultCode::noIssuer();
        $pathPaymentStrictSendResult->value = $asset;

        return $pathPaymentStrictSendResult;
    }

    /**
     * Return the underlying value.
     *
     * @return PathPaymentStrictSendResultSuccess|Asset|null
     */
    public function unwrap(): PathPaymentStrictSendResultSuccess|Asset|null
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
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictSendResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param PathPaymentStrictSendResultCode $code
     * @param PathPaymentStrictSendResult|Asset|null $value
     * @return static
     */
    public static function simulate(PathPaymentStrictSendResultCode $code, PathPaymentStrictSendResult|Asset $value = null): static
    {
        $pathPaymentStrictSendResult = new static();
        $pathPaymentStrictSendResult->discriminator = $code;
        $pathPaymentStrictSendResult->value = $value;

        return $pathPaymentStrictSendResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof PathPaymentStrictSendResultCode
            && $this->discriminator->getType() == PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictSendResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#path-payment-strict-send
     * @var array<string, string>
     */
    protected $messages = [
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SUCCESS            => "The operation was successful.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_MALFORMED          => "The input to this path payment is invalid.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDERFUNDED        => "The source account (sender) does not have enough funds to send and still satisfy its selling liabilities. Note that if sending XLM then the sender must additionally maintain its minimum XLM reserve.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NO_TRUST       => "The source account does not trust the issuer of the asset it is trying to send.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_SRC_NOT_AUTHORIZED => "The source account is not authorized to send this payment.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_DESTINATION     => "The destination account does not exist.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_TRUST           => "The destination account does not trust the issuer of the asset being sent.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NOT_AUTHORIZED     => "The destination account is not authorized by the asset's issuer to hold the asset.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_LINE_FULL          => "The destination account does not have sufficient limits to SEND destination amount and still satisfy its buying liabilities.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_NO_ISSUER          => "One asset is missing an issuer",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_TOO_FEW_OFFERS     => "There is no path of offers connecting the send asset and destination asset. Stellar only considers paths of length 5 or shorter.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_OFFER_CROSS_SELF   => "The payment would cross one of its own offers.",
        PathPaymentStrictSendResultCode::PATH_PAYMENT_STRICT_SEND_UNDER_DESTMIN      => "The paths that could send destination amount of destination asset would fall short of destination min.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PathPaymentStrictSendResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
