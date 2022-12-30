<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ManageSellOfferResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ManageSellOfferResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS             => ManageOfferSuccessResult::class,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_MALFORMED           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_TRUST       => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_TRUST        => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED  => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_LINE_FULL           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_UNDERFUNDED         => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_CROSS_SELF          => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_ISSUER      => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_ISSUER       => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_NOT_FOUND           => XDR::VOID,
            ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE         => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping a ManageOfferSuccessResult object.
     *
     * @param ManageOfferSuccessResult $manageOfferSuccessResult
     * @return static
     */
    public static function wrapManageOfferSuccessResult(ManageOfferSuccessResult $manageOfferSuccessResult): static
    {
        $manageSellOfferResult = new static();
        $manageSellOfferResult->discriminator = ManageSellOfferResultCode::success();
        $manageSellOfferResult->value = $manageOfferSuccessResult;

        return $manageSellOfferResult;
    }

    /**
     * Return the underlying value.
     *
     * @return ManageOfferSuccessResult|null
     */
    public function unwrap(): ?ManageOfferSuccessResult
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
        if (isset($this->discriminator) && $this->discriminator instanceof ManageSellOfferResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ManageSellOfferResultCode $code
     * @param ManageOfferSuccessResult|null $success
     * @return static
     */
    public static function simulate(ManageSellOfferResultCode $code, ManageOfferSuccessResult $success = null): static
    {
        $manageSellOfferResult = new static();
        $manageSellOfferResult->discriminator = $code;
        $manageSellOfferResult->value = $success;

        return $manageSellOfferResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ManageSellOfferResultCode
            && $this->discriminator->getType() == ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ManageSellOfferResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-sell-offer
     * @var array<string, string>
     */
    protected $messages = [
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_SUCCESS             => "The operation was successful.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_MALFORMED           => "The input is incorrect and would result in an invalid offer.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_TRUST       => "The account creating the offer does not have a trustline for the asset it is selling.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_TRUST        => "The account creating the offer does not have a trustline for the asset it is buying.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NOT_AUTHORIZED => "The account creating the offer is not authorized to sell this asset.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NOT_AUTHORIZED  => "The account creating the offer is not authorized to buy this asset.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_LINE_FULL           => "The account creating the offer does not have sufficient limits to receive buying and still satisfy its buying liabilities.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_UNDERFUNDED         => "The account creating the offer does not have sufficient limits to send selling and still satisfy its selling liabilities. Note that if selling XLM then the account must additionally maintain its minimum XLM reserve, which is calculated assuming this offer will not completely execute immediately.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_CROSS_SELF          => "The account has opposite offer of equal or lesser price active, so the account creating this offer would immediately cross itself.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_SELL_NO_ISSUER      => "The asset being sold does not have an issuer.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_BUY_NO_ISSUER       => "The asset being bought does not have an issuer",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_NOT_FOUND           => "An offer with that offerID cannot be found.",
        ManageSellOfferResultCode::MANAGE_SELL_OFFER_LOW_RESERVE         => "The account creating this offer does not have enough XLM to satisfy the minimum XLM reserve increase caused by adding a subentry and still satisfy its XLM selling liabilities. For every offer an account creates, the minimum amount of XLM that account must hold will increase.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ManageSellOfferResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
