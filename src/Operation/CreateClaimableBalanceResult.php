<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class CreateClaimableBalanceResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return CreateClaimableBalanceResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS        => ClaimableBalanceId::class,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED      => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE    => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NO_TRUST       => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED => XDR::VOID,
            CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED    => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping a claimable balance Id.
     *
     * @param ClaimableBalanceId $claimableBalanceId
     * @return static
     */
    public static function wrapClaimableBalanceId(ClaimableBalanceId $claimableBalanceId): static
    {
        $createClaimableBalanceResult = new static();
        $createClaimableBalanceResult->discriminator = CreateClaimableBalanceResultCode::success();
        $createClaimableBalanceResult->value = $claimableBalanceId;

        return $createClaimableBalanceResult;
    }

    /**
     * Return the underlying value.
     *
     * @return ClaimableBalanceId|null
     */
    public function unwrap(): ?ClaimableBalanceId
    {
        return $this->value;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof CreateClaimableBalanceResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param CreateClaimableBalanceResultCode $code
     * @param ClaimableBalanceId|null $balanceId
     * @return static
     */
    public static function simulate(CreateClaimableBalanceResultCode $code, ClaimableBalanceId $balanceId = null): static
    {
        $createClaimableBalanceResult = new static();
        $createClaimableBalanceResult->discriminator = $code;
        $createClaimableBalanceResult->value = $balanceId;

        return $createClaimableBalanceResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof CreateClaimableBalanceResultCode
            && $this->discriminator->getType() == CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof CreateClaimableBalanceResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-claimable-balance
     * @var array<string, string>
     */
    protected $messages = [
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_SUCCESS        => "The operation was successful.",
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_MALFORMED      => "The input to this operation is invalid.",
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_LOW_RESERVE    => "The account creating this entry does not have enough XLM to satisfy the minimum XLM reserve increase caused by adding a ClaimableBalanceEntry. For every claimant in the list, the minimum amount of XLM this account must hold will increase by baseReserve.",
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NO_TRUST       => "The source account does not trust the issuer of the asset it is trying to include in the ClaimableBalanceEntry.",
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_NOT_AUTHORIZED => "The source account is not authorized to transfer this asset.",
        CreateClaimableBalanceResultCode::CREATE_CLAIMABLE_BALANCE_UNDERFUNDED    => "The source account does not have enough funds to transfer amount of this asset to the ClaimableBalanceEntry.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof CreateClaimableBalanceResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
