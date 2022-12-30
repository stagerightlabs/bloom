<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class SetTrustLineFlagsResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return SetTrustLineFlagsResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS       => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_MALFORMED     => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_CANT_REVOKE   => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_INVALID_STATE => XDR::VOID,
            SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE   => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $setTrustLineFlagsResult = new static();
        $setTrustLineFlagsResult->discriminator = SetTrustLineFlagsResultCode::success();
        $setTrustLineFlagsResult->value = null;

        return $setTrustLineFlagsResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof SetTrustLineFlagsResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param SetTrustLineFlagsResultCode $code
     * @return static
     */
    public static function simulate(SetTrustLineFlagsResultCode $code): static
    {
        $setTrustLineFlagsResult = new static();
        $setTrustLineFlagsResult->discriminator = $code;
        $setTrustLineFlagsResult->value = null;

        return $setTrustLineFlagsResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof SetTrustLineFlagsResultCode
            && $this->discriminator->getType() == SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof SetTrustLineFlagsResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-trustline-flags
     * @var array<string, string>
     */
    protected $messages = [
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_SUCCESS       => "The operation was successful.",
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_MALFORMED     => "This can happen for a number of reasons: the asset specified by AssetCode and AssetIssuer is invalid; the asset issuer isn't the source account; the Trustor is the source account; the native asset is specified; or the flags being set/cleared conflict or are otherwise invalid.",
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_NO_TRUST_LINE => "The Trustor does not have a trustline with the issuer performing this operation.",
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_CANT_REVOKE   => "The issuer is trying to revoke the trustline authorization of Trustor, but it cannot do so because AUTH_REVOCABLE_FLAG is not set on the account.",
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_INVALID_STATE => "If the final state of the trustline has both AUTHORIZED_FLAG (1) and AUTHORIZED_TO_MAINTAIN_LIABILITIES_FLAG (2) set, which are mutually exclusive.",
        SetTrustLineFlagsResultCode::SET_TRUST_LINE_FLAGS_LOW_RESERVE   => "Claimable balances can't be created on revocation of asset (or pool share) trustlines associated with a liquidity pool due to low reserves.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof SetTrustLineFlagsResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
