<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ClaimClaimableBalanceResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClaimClaimableBalanceResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS        => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM   => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_LINE_FULL      => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NO_TRUST       => XDR::VOID,
            ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $claimClaimableBalanceResult = new static();
        $claimClaimableBalanceResult->discriminator = ClaimClaimableBalanceResultCode::success();
        $claimClaimableBalanceResult->value = null;

        return $claimClaimableBalanceResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClaimClaimableBalanceResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ClaimClaimableBalanceResultCode $code
     * @return static
     */
    public static function simulate(ClaimClaimableBalanceResultCode $code): static
    {
        $claimClaimableBalanceResult = new static();
        $claimClaimableBalanceResult->discriminator = $code;
        $claimClaimableBalanceResult->value = null;

        return $claimClaimableBalanceResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ClaimClaimableBalanceResultCode
            && $this->discriminator->getType() == ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ClaimClaimableBalanceResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#claim-claimable-balance
     * @var array<string, string>
     */
    protected $messages = [
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_SUCCESS        => "The operation was successful.",
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_DOES_NOT_EXIST => "There is no existing Claimable Balance Entry that matches the input Balance ID.",
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_CANNOT_CLAIM   => "There is no claimant that matches the source account, or the claimants predicate is not satisfied.",
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_LINE_FULL      => "The account claiming the ClaimableBalanceEntry does not have sufficient limits to receive amount of the asset and still satisfy its buying liabilities.",
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NO_TRUST       => "The source account does not trust the issuer of the asset it is trying to claim in the ClaimableBalanceEntry.",
        ClaimClaimableBalanceResultCode::CLAIM_CLAIMABLE_BALANCE_NOT_AUTHORIZED => "The source account is not authorized to claim the asset in the ClaimableBalanceEntry.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ClaimClaimableBalanceResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
