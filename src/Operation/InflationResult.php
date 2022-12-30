<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

/**
 * Inflation has been deprecated as of CAP-26; this result definition
 * is here only for the sake of backwards compatibility.
 */
final class InflationResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return InflationResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            InflationResultCode::INFLATION_SUCCESS  => InflationPayout::class,
            InflationResultCode::INFLATION_NOT_TIME => XDR::VOID,
        ];
    }

    /**
     * Create a new instance by wrapping an InflationPayout object.
     *
     * @param InflationPayout $inflationPayout
     * @return static
     */
    public static function wrapInflationPayout(InflationPayout $inflationPayout): static
    {
        $inflationResult = new static();
        $inflationResult->discriminator = InflationResultCode::success();
        $inflationResult->value = $inflationPayout;

        return $inflationResult;
    }

    /**
     * Return the underlying value.
     *
     * @return InflationPayout|null
     */
    public function unwrap(): ?InflationPayout
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
        if (isset($this->discriminator) && $this->discriminator instanceof InflationResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param InflationResultCode $code
     * @param InflationPayout|null $payout
     * @return static
     */
    public static function simulate(InflationResultCode $code, InflationPayout $payout = null): static
    {
        $inflationResult = new static();
        $inflationResult->discriminator = $code;
        $inflationResult->value = $payout;

        return $inflationResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof InflationResultCode
            && $this->discriminator->getType() == InflationResultCode::INFLATION_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof InflationResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/encyclopedia/inflation
     * @var array<string, string>
     */
    protected $messages = [
        InflationResultCode::INFLATION_SUCCESS  => "The operation was successful.",
        InflationResultCode::INFLATION_NOT_TIME => "The operation was not successful.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof InflationResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
