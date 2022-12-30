<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ManageDataResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ManageDataResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ManageDataResultCode::MANAGE_DATA_SUCCESS           => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_NOT_SUPPORTED_YET => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_NAME_NOT_FOUND    => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_LOW_RESERVE       => XDR::VOID,
            ManageDataResultCode::MANAGE_DATA_INVALID_NAME      => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $manageDataResult = new static();
        $manageDataResult->discriminator = ManageDataResultCode::success();
        $manageDataResult->value = null;

        return $manageDataResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ManageDataResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param ManageDataResultCode $code
     * @return static
     */
    public static function simulate(ManageDataResultCode $code): static
    {
        $manageDataResult = new static();
        $manageDataResult->discriminator = $code;
        $manageDataResult->value = null;

        return $manageDataResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof ManageDataResultCode
            && $this->discriminator->getType() == ManageDataResultCode::MANAGE_DATA_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof ManageDataResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#manage-data
     * @var array<string, string>
     */
    protected $messages = [
        ManageDataResultCode::MANAGE_DATA_SUCCESS           => "The operation was successful.",
        ManageDataResultCode::MANAGE_DATA_NOT_SUPPORTED_YET => "The network hasn't moved to this protocol change yet. This failure means the network doesn't support this feature yet.",
        ManageDataResultCode::MANAGE_DATA_NAME_NOT_FOUND    => "Trying to remove a Data Entry that isn't there. This will happen if Name is set (and Value isn't) but the Account doesn't have a DataEntry with that Name.",
        ManageDataResultCode::MANAGE_DATA_LOW_RESERVE       => "This account does not have enough XLM to satisfy the minimum XLM reserve increase caused by adding a subentry and still satisfy its XLM selling liabilities. For every new DataEntry added to an account, the minimum reserve of XLM that account must hold increases.",
        ManageDataResultCode::MANAGE_DATA_INVALID_NAME      => "Name not a valid string.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ManageDataResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
