<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class PaymentResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return PaymentResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            PaymentResultCode::PAYMENT_SUCCESS            => XDR::VOID,
            PaymentResultCode::PAYMENT_MALFORMED          => XDR::VOID,
            PaymentResultCode::PAYMENT_UNDERFUNDED        => XDR::VOID,
            PaymentResultCode::PAYMENT_SRC_NO_TRUST       => XDR::VOID,
            PaymentResultCode::PAYMENT_SRC_NOT_AUTHORIZED => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_DESTINATION     => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_TRUST           => XDR::VOID,
            PaymentResultCode::PAYMENT_NOT_AUTHORIZED     => XDR::VOID,
            PaymentResultCode::PAYMENT_LINE_FULL          => XDR::VOID,
            PaymentResultCode::PAYMENT_NO_ISSUER          => XDR::VOID,
        ];
    }

    /**
     * Create a success instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $createAccountResult = new static();
        $createAccountResult->discriminator = PaymentResultCode::success();
        $createAccountResult->value = null;

        return $createAccountResult;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return null
     */
    public function unwrap()
    {
        return null;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PaymentResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param PaymentResultCode $code
     * @return static
     */
    public static function simulate(PaymentResultCode $code): static
    {
        $paymentResult = new static();
        $paymentResult->discriminator = $code;
        $paymentResult->value = null;

        return $paymentResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof PaymentResultCode
            && $this->discriminator->getType() == PaymentResultCode::PAYMENT_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof PaymentResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#payment
     * @var array<string, string>
     */
    protected $messages = [
        PaymentResultCode::PAYMENT_SUCCESS            => "The operation was successful.",
        PaymentResultCode::PAYMENT_MALFORMED          => "The input to the payment is invalid.",
        PaymentResultCode::PAYMENT_UNDERFUNDED        => "The source account (sender) does not have enough funds to send amount and still satisfy its selling liabilities. Note that if sending XLM then the sender must additionally maintain its minimum XLM reserve.",
        PaymentResultCode::PAYMENT_SRC_NO_TRUST       => "The source account does not trust the issuer of the asset it is trying to send.",
        PaymentResultCode::PAYMENT_SRC_NOT_AUTHORIZED => "The source account is not authorized to send this payment.",
        PaymentResultCode::PAYMENT_NO_DESTINATION     => "The receiving account does not exist. Note that this error will not be returned if the receiving account is the issuer of asset.",
        PaymentResultCode::PAYMENT_NO_TRUST           => "The receiver does not trust the issuer of the asset being sent.",
        PaymentResultCode::PAYMENT_NOT_AUTHORIZED     => "The destination account is not authorized by the asset's issuer to hold the asset.",
        PaymentResultCode::PAYMENT_LINE_FULL          => "The destination account (receiver) does not have sufficient limits to receive amount and still satisfy its buying liabilities.",
        PaymentResultCode::PAYMENT_NO_ISSUER          => "The asset does not have a valid issuer.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof PaymentResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
