<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ClawbackResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClawbackResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClawbackResultCode::CLAWBACK_SUCCESS              => XDR::VOID,
            ClawbackResultCode::CLAWBACK_MALFORMED            => XDR::VOID,
            ClawbackResultCode::CLAWBACK_NOT_CLAWBACK_ENABLED => XDR::VOID,
            ClawbackResultCode::CLAWBACK_NO_TRUST             => XDR::VOID,
            ClawbackResultCode::CLAWBACK_UNDERFUNDED          => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $clawbackResult = new static();
        $clawbackResult->discriminator = ClawbackResultCode::success();
        $clawbackResult->value = null;

        return $clawbackResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ClawbackResultCode $code
     * @return static
     */
    public static function simulate(ClawbackResultCode $code): static
    {
        $clawbackResult = new static();
        $clawbackResult->discriminator = $code;
        $clawbackResult->value = null;

        return $clawbackResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ClawbackResultCode
            && $this->discriminator->getType() == ClawbackResultCode::CLAWBACK_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#clawback
     * @var array<string, string>
     */
    protected $messages = [
        ClawbackResultCode::CLAWBACK_SUCCESS              => "The operation was successful.",
        ClawbackResultCode::CLAWBACK_MALFORMED            => "The input to the clawback is invalid.",
        ClawbackResultCode::CLAWBACK_NOT_CLAWBACK_ENABLED => "The trustline between From and the issuer account for this Asset does not have clawback enabled.",
        ClawbackResultCode::CLAWBACK_NO_TRUST             => "The From account does not trust the issuer of the asset.",
        ClawbackResultCode::CLAWBACK_UNDERFUNDED          => "The From account does not have a sufficient available balance of the asset (after accounting for selling liabilities).",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
