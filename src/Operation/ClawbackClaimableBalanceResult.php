<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ClawbackClaimableBalanceResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClawbackClaimableBalanceResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS              => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST       => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER           => XDR::VOID,
            ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $clawbackClaimableBalanceResult = new static();
        $clawbackClaimableBalanceResult->discriminator = ClawbackClaimableBalanceResultCode::success();
        $clawbackClaimableBalanceResult->value = null;

        return $clawbackClaimableBalanceResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackClaimableBalanceResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ClawbackClaimableBalanceResultCode $code
     * @return static
     */
    public static function simulate(ClawbackClaimableBalanceResultCode $code): static
    {
        $clawbackClaimableBalanceResult = new static();
        $clawbackClaimableBalanceResult->discriminator = $code;
        $clawbackClaimableBalanceResult->value = null;

        return $clawbackClaimableBalanceResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ClawbackClaimableBalanceResultCode
            && $this->discriminator->getType() == ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackClaimableBalanceResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#clawback-claimable-balance
     * @var array<string, string>
     */
    protected $messages = [
        ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_SUCCESS              => "The operation was successful.",
        ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_DOES_NOT_EXIST       => "There is no existing ClaimableBalanceEntry that matches the input BalanceID.",
        ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_ISSUER           => "The source account is not the issuer of the asset in the claimable balance.",
        ClawbackClaimableBalanceResultCode::CLAWBACK_CLAIMABLE_BALANCE_NOT_CLAWBACK_ENABLED => "The CLAIMABLE_BALANCE_CLAWBACK_ENABLED_FLAG is not set for this trustline.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClawbackClaimableBalanceResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
