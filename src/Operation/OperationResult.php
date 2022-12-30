<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class OperationResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return OperationResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            OperationResultCode::INNER               => OperationResultTr::class,
            OperationResultCode::BAD_AUTH            => XDR::VOID,
            OperationResultCode::NO_ACCOUNT          => XDR::VOID,
            OperationResultCode::NOT_SUPPORTED       => XDR::VOID,
            OperationResultCode::TOO_MANY_SUBENTRIES => XDR::VOID,
            OperationResultCode::EXCEEDED_WORK_LIMIT => XDR::VOID,
            OperationResultCode::TOO_MANY_SPONSORING => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping an OperationResultTr.
     *
     * @param OperationResultTr $result
     * @return static
     */
    public static function wrapOperationResultTr(OperationResultTr $result): static
    {
        $operationResult = new static();
        $operationResult->discriminator = OperationResultCode::inner();
        $operationResult->value = $result;

        return $operationResult;
    }

    /**
     * Return the underlying OperationResultTr if present.
     *
     * @return OperationResultTr|null
     */
    public function unwrap(): ?OperationResultTr
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the result code.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof OperationResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param OperationResultCode $code
     * @param OperationResultTr|null $result
     * @return static
     */
    public static function simulate(OperationResultCode $code, OperationResultTr $result = null): static
    {
        $operationResult = new static();
        $operationResult->discriminator = $code;
        $operationResult->value = $result;

        return $operationResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof OperationResultCode
            && $this->discriminator->getType() == OperationResultCode::INNER;
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
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        if ($this->unwrap() instanceof OperationResultTr && $this->unwrap()->unwrap() instanceof OperationOutcome) {
            return $this->unwrap()->unwrap()->getErrorMessage();
        }

        if (isset($this->discriminator) && $this->discriminator instanceof OperationResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/api/errors/result-codes/operations/
     * @var array<string, string>
     */
    protected $messages = [
        OperationResultCode::INNER               => 'See the included context for details.',
        OperationResultCode::BAD_AUTH            => 'There are too few valid signatures, or the transaction was submitted to the wrong network.',
        OperationResultCode::NO_ACCOUNT          => 'Source account not found',
        OperationResultCode::NOT_SUPPORTED       => 'The operation is not supported at this time.',
        OperationResultCode::TOO_MANY_SUBENTRIES => 'Max number of subentries (1000) already reached',
        OperationResultCode::EXCEEDED_WORK_LIMIT => 'Operation did too much work',
        OperationResultCode::TOO_MANY_SPONSORING => 'Source account is sponsoring too many entries',
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if ($this->unwrap() instanceof OperationResultTr && $this->unwrap()->unwrap() instanceof OperationOutcome) {
            return $this->unwrap()->unwrap()->getErrorCode();
        }

        if (isset($this->discriminator) && $this->discriminator instanceof OperationResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
