<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class AccountMergeResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return AccountMergeResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS         => Int64::class,
            AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED       => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_NO_ACCOUNT      => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_IMMUTABLE_SET   => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_HAS_SUB_ENTRIES => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_SEQNUM_TOO_FAR  => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_DEST_FULL       => XDR::VOID,
            AccountMergeResultCode::ACCOUNT_MERGE_IS_SPONSOR      => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance by wrapping an Int64.
     *
     * @param Int64 $int64
     * @return static
     */
    public static function wrapInt64(Int64 $int64): static
    {
        $accountMergeResult = new static();
        $accountMergeResult->discriminator = AccountMergeResultCode::success();
        $accountMergeResult->value = $int64;

        return $accountMergeResult;
    }

    /**
     * Return the underlying value.
     *
     * @return Int64|null
     */
    public function unwrap(): ?Int64
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
        if (isset($this->discriminator) && $this->discriminator instanceof AccountMergeResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param AccountMergeResultCode $discriminator
     * @param Int64|null $value
     * @return static
     */
    public static function simulate(AccountMergeResultCode $discriminator, Int64 $value = null): static
    {
        $accountMergeResult = new static();
        $accountMergeResult->discriminator = $discriminator;
        $accountMergeResult->value = $value;

        return $accountMergeResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof AccountMergeResultCode
            && $this->discriminator->getType() == AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof AccountMergeResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#account-merge
     * @var array<string, string>
     */
    protected $messages = [
        AccountMergeResultCode::ACCOUNT_MERGE_SUCCESS         => "The operation was successful.",
        AccountMergeResultCode::ACCOUNT_MERGE_MALFORMED       => "The operation is malformed because the source account cannot merge with itself. The destination must be a different account.",
        AccountMergeResultCode::ACCOUNT_MERGE_NO_ACCOUNT      => "The destination account does not exist.",
        AccountMergeResultCode::ACCOUNT_MERGE_IMMUTABLE_SET   => "The source account has AUTH_IMMUTABLE flag set.",
        AccountMergeResultCode::ACCOUNT_MERGE_HAS_SUB_ENTRIES => "The source account has trustlines/offers.",
        AccountMergeResultCode::ACCOUNT_MERGE_SEQNUM_TOO_FAR  => "Source's account sequence number is too high. It must be less than (ledgerSeq << 32) = (ledgerSeq * 0x100000000).",
        AccountMergeResultCode::ACCOUNT_MERGE_DEST_FULL       => "The destination account cannot receive the balance of the source account and still satisfy its lumen buying liabilities.",
        AccountMergeResultCode::ACCOUNT_MERGE_IS_SPONSOR      => "The source account is a sponsor.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof AccountMergeResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
