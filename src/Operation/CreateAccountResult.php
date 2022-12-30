<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class CreateAccountResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return CreateAccountResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-account
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS        => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED      => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_UNDERFUNDED    => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_LOW_RESERVE    => XDR::VOID,
            CreateAccountResultCode::CREATE_ACCOUNT_ALREADY_EXISTS => XDR::VOID,
        ];
    }

    /**
     * Create a success instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $createAccountResult = new static();
        $createAccountResult->discriminator = CreateAccountResultCode::success();
        $createAccountResult->value = null;

        return $createAccountResult;
    }

    /**
     * Unwrap the underlying value.
     *
     * @return null
     */
    public function unwrap()
    {
        return null;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof CreateAccountResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param CreateAccountResultCode $code
     * @return static
     */
    public static function simulate(CreateAccountResultCode $code): static
    {
        $createAccountResult = new static();
        $createAccountResult->discriminator = $code;
        $createAccountResult->value = null;

        return $createAccountResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof CreateAccountResultCode
            && $this->discriminator->getType() == CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof CreateAccountResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#create-account
     * @var array<string, string>
     */
    protected $messages = [
        CreateAccountResultCode::CREATE_ACCOUNT_SUCCESS        => "The operation was successful.",
        CreateAccountResultCode::CREATE_ACCOUNT_MALFORMED      => "The destination is invalid.",
        CreateAccountResultCode::CREATE_ACCOUNT_UNDERFUNDED    => "The source account performing the command does not have enough funds to give destination the starting balance amount of XLM and still maintain its minimum XLM reserve plus satisfy its XLM selling liabilities.",
        CreateAccountResultCode::CREATE_ACCOUNT_LOW_RESERVE    => "This operation would create an account with fewer than the minimum number of XLM an account must hold.",
        CreateAccountResultCode::CREATE_ACCOUNT_ALREADY_EXISTS => "The destination account already exists.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof CreateAccountResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
