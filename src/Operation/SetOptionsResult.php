<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class SetOptionsResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return SetOptionsResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            SetOptionsResultCode::SET_OPTIONS_SUCCESS                 => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE             => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_TOO_MANY_SIGNERS        => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_BAD_FLAGS               => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_INVALID_INFLATION       => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_CANT_CHANGE             => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_UNKNOWN_FLAG            => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE  => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_BAD_SIGNER              => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_INVALID_HOME_DOMAIN     => XDR::VOID,
            SetOptionsResultCode::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $setOptionsResult = new static();
        $setOptionsResult->discriminator = SetOptionsResultCode::success();
        $setOptionsResult->value = null;

        return $setOptionsResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof SetOptionsResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param SetOptionsResultCode $code
     * @return static
     */
    public static function simulate(SetOptionsResultCode $code): static
    {
        $setOptionsResult = new static();
        $setOptionsResult->discriminator = $code;
        $setOptionsResult->value = null;

        return $setOptionsResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof SetOptionsResultCode
            && $this->discriminator->getType() == SetOptionsResultCode::SET_OPTIONS_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof SetOptionsResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#set-options
     * @var array<string, string>
     */
    protected $messages = [
        SetOptionsResultCode::SET_OPTIONS_SUCCESS                 => "The operation was successful.",
        SetOptionsResultCode::SET_OPTIONS_LOW_RESERVE             => "This account does not have enough XLM to satisfy the minimum XLM reserve increase caused by adding a subentry and still satisfy its XLM selling liabilities. For every new signer added to an account, the minimum reserve of XLM that account must hold increases.",
        SetOptionsResultCode::SET_OPTIONS_TOO_MANY_SIGNERS        => "20 is the maximum number of signers an account can have, and adding another signer would exceed that.",
        SetOptionsResultCode::SET_OPTIONS_BAD_FLAGS               => "The flags set and/or cleared are invalid by themselves or in combination.",
        SetOptionsResultCode::SET_OPTIONS_INVALID_INFLATION       => "The destination account set in the inflation field does not exist.",
        SetOptionsResultCode::SET_OPTIONS_CANT_CHANGE             => "This account can no longer change the option it wants to change.",
        SetOptionsResultCode::SET_OPTIONS_UNKNOWN_FLAG            => "The account is trying to set a flag that is unknown.",
        SetOptionsResultCode::SET_OPTIONS_THRESHOLD_OUT_OF_RANGE  => "The value for a key weight or threshold is invalid.",
        SetOptionsResultCode::SET_OPTIONS_BAD_SIGNER              => "Any additional signers added to the account cannot be the master key.",
        SetOptionsResultCode::SET_OPTIONS_INVALID_HOME_DOMAIN     => "Home domain is malformed.",
        SetOptionsResultCode::SET_OPTIONS_AUTH_REVOCABLE_REQUIRED => "The Auth Revocable flag is required",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof SetOptionsResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
