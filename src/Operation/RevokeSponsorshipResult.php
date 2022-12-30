<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class RevokeSponsorshipResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return RevokeSponsorshipResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS           => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_DOES_NOT_EXIST    => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_NOT_SPONSOR       => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE       => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE => XDR::VOID,
            RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_MALFORMED         => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $revokeSponsorshipResult = new static();
        $revokeSponsorshipResult->discriminator = RevokeSponsorshipResultCode::success();
        $revokeSponsorshipResult->value = null;

        return $revokeSponsorshipResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof RevokeSponsorshipResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param RevokeSponsorshipResultCode $code
     * @return static
     */
    public static function simulate(RevokeSponsorshipResultCode $code): static
    {
        $revokeSponsorshipResult = new static();
        $revokeSponsorshipResult->discriminator = $code;
        $revokeSponsorshipResult->value = null;

        return $revokeSponsorshipResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof RevokeSponsorshipResultCode
            && $this->discriminator->getType() == RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof RevokeSponsorshipResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#revoke-sponsorship
     * @var array<string, string>
     */
    protected $messages = [
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_SUCCESS           => "The operation was successful.",
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_DOES_NOT_EXIST    => "The ledgerEntry for LedgerKey doesn’t exist, the account ID on signer doesn’t exist, or the Signer Key doesn’t exist on account ID’s account.",
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_NOT_SPONSOR       => "If the ledgerEntry/signer is sponsored, then the source account must be the sponsor. If the ledgerEntry/signer is not sponsored, the source account must be the owner. This error will be thrown otherwise.",
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_LOW_RESERVE       => "The sponsored account does not have enough XLM to satisfy the minimum balance increase caused by revoking sponsorship on a ledgerEntry/signer it owns, or the sponsor of the source account doesn’t have enough XLM to satisfy the minimum balance increase caused by sponsoring a transferred ledgerEntry/signer.",
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_ONLY_TRANSFERABLE => "Sponsorship cannot be removed from this ledgerEntry. This error will happen if the user tries to remove the sponsorship from a ClaimableBalanceEntry.",
        RevokeSponsorshipResultCode::REVOKE_SPONSORSHIP_MALFORMED         => "One or more of the inputs to the operation was malformed.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof RevokeSponsorshipResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
