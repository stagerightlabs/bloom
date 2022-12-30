<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AllowTrustResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return AllowTrustResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            AllowTrustResultCode::ALLOW_TRUST_SUCCESS            => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_MALFORMED          => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_NO_TRUST_LINE      => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_TRUST_NOT_REQUIRED => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_CANT_REVOKE        => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_SELF_NOT_ALLOWED   => XDR::VOID,
            AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE        => XDR::VOID,
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
        $changeTrustResult->discriminator = AllowTrustResultCode::success();
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
        if (isset($this->discriminator) && $this->discriminator instanceof AllowTrustResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status.
     *
     * @param AllowTrustResultCode $discriminator
     * @return static
     */
    public static function simulate(AllowTrustResultCode $discriminator): static
    {
        $allowTrustResult = new static();
        $allowTrustResult->discriminator = $discriminator;
        $allowTrustResult->value = null;

        return $allowTrustResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof AllowTrustResultCode
            && $this->discriminator->getType() == AllowTrustResultCode::ALLOW_TRUST_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof AllowTrustResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#allow-trust
     * @var array<string, string>
     */
    protected $messages = [
        AllowTrustResultCode::ALLOW_TRUST_SUCCESS            => "The operation was successful.",
        AllowTrustResultCode::ALLOW_TRUST_MALFORMED          => "The asset specified in type is invalid. In addition, this error happens when the native asset is specified.",
        AllowTrustResultCode::ALLOW_TRUST_NO_TRUST_LINE      => "The trustor does not have a trustline with the issuer performing this operation.",
        AllowTrustResultCode::ALLOW_TRUST_TRUST_NOT_REQUIRED => "The source account (issuer performing this operation) does not require trust. In other words, it does not have the flag AUTH_REQUIRED_FLAG set.",
        AllowTrustResultCode::ALLOW_TRUST_CANT_REVOKE        => "The source account is trying to revoke the trustline of the trustor, but it cannot do so.",
        AllowTrustResultCode::ALLOW_TRUST_SELF_NOT_ALLOWED   => "The source account attempted to allow a trustline for itself, which is not allowed because an account cannot create a trustline with itself.",
        AllowTrustResultCode::ALLOW_TRUST_LOW_RESERVE        => "Claimable balances can't be created on revocation of asset (or pool share) trustlines associated with a liquidity pool due to low reserves.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof AllowTrustResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
