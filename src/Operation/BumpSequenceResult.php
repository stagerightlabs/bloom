<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class BumpSequenceResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return BumpSequenceResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS => XDR::VOID,
            BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $bumpSequenceResult = new static();
        $bumpSequenceResult->discriminator = BumpSequenceResultCode::success();
        $bumpSequenceResult->value = null;

        return $bumpSequenceResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof BumpSequenceResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param BumpSequenceResultCode $code
     * @return static
     */
    public static function simulate(BumpSequenceResultCode $code): static
    {
        $bumpSequenceResult = new static();
        $bumpSequenceResult->discriminator = $code;
        $bumpSequenceResult->value = null;

        return $bumpSequenceResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof BumpSequenceResultCode
            && $this->discriminator->getType() == BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof BumpSequenceResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#bump-sequence
     * @var array<string, string>
     */
    protected $messages = [
        BumpSequenceResultCode::BUMP_SEQUENCE_SUCCESS => "The operation was successful.",
        BumpSequenceResultCode::BUMP_SEQUENCE_BAD_SEQ => "The specified bumpTo sequence number is not a valid sequence number. It must be between 0 and INT64_MAX (9223372036854775807 or 0x7fffffffffffffff).",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof BumpSequenceResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
