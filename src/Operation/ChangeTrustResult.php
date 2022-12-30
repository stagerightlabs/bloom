<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ChangeTrustResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ChangeTrustResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ChangeTrustResultCode::CHANGE_TRUST_SUCCESS                       => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_MALFORMED                     => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_NO_ISSUER                     => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_INVALID_LIMIT                 => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE                   => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_SELF_NOT_ALLOWED              => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_TRUST_LINE_MISSING            => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_CANNOT_DELETE                 => XDR::VOID,
            ChangeTrustResultCode::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $changeTrustResult = new static();
        $changeTrustResult->discriminator = ChangeTrustResultCode::success();
        $changeTrustResult->value = null;

        return $changeTrustResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ChangeTrustResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ChangeTrustResultCode $code
     * @return static
     */
    public static function simulate(ChangeTrustResultCode $code): static
    {
        $changeTrustResult = new static();
        $changeTrustResult->discriminator = $code;
        $changeTrustResult->value = null;

        return $changeTrustResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ChangeTrustResultCode
            && $this->discriminator->getType() == ChangeTrustResultCode::CHANGE_TRUST_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ChangeTrustResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#change-trust
     * @var array<string, string>
     */
    protected $messages = [
        ChangeTrustResultCode::CHANGE_TRUST_SUCCESS                       => "The operation was successful.",
        ChangeTrustResultCode::CHANGE_TRUST_MALFORMED                     => "The input to this operation is invalid.",
        ChangeTrustResultCode::CHANGE_TRUST_NO_ISSUER                     => "The issuer of the asset cannot be found.",
        ChangeTrustResultCode::CHANGE_TRUST_INVALID_LIMIT                 => "The limit is not sufficient to hold the current balance of the trustline and still satisfy its buying liabilities. This error occurs when attempting to remove a trustline with a non-zero asset balance.",
        ChangeTrustResultCode::CHANGE_TRUST_LOW_RESERVE                   => "This account does not have enough XLM to satisfy the minimum XLM reserve increase caused by adding a subentry and still satisfy its XLM selling liabilities. For every new trustline added to an account, the minimum reserve of XLM that account must hold increases.",
        ChangeTrustResultCode::CHANGE_TRUST_SELF_NOT_ALLOWED              => "The source account attempted to create a trustline for itself, which is not allowed.",
        ChangeTrustResultCode::CHANGE_TRUST_TRUST_LINE_MISSING            => "The asset trustline is missing for the liquidity pool.",
        ChangeTrustResultCode::CHANGE_TRUST_CANNOT_DELETE                 => "The asset trustline is still referenced by a liquidity pool.",
        ChangeTrustResultCode::CHANGE_TRUST_NOT_AUTH_MAINTAIN_LIABILITIES => "The asset trustline is de-authorized.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ChangeTrustResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
