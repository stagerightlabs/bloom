<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class EndSponsoringFutureReservesResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return EndSponsoringFutureReservesResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS       => XDR::VOID,
            EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $endSponsoringFutureReservesResult = new static();
        $endSponsoringFutureReservesResult->discriminator = EndSponsoringFutureReservesResultCode::success();
        $endSponsoringFutureReservesResult->value = null;

        return $endSponsoringFutureReservesResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof EndSponsoringFutureReservesResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param EndSponsoringFutureReservesResultCode $code
     * @return static
     */
    public static function simulate(EndSponsoringFutureReservesResultCode $code): static
    {
        $endSponsoringFutureReservesResult = new static();
        $endSponsoringFutureReservesResult->discriminator = $code;
        $endSponsoringFutureReservesResult->value = null;

        return $endSponsoringFutureReservesResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof EndSponsoringFutureReservesResultCode
            && $this->discriminator->getType() == EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof EndSponsoringFutureReservesResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#end-sponsoring-future-reserves
     * @var array<string, string>
     */
    protected $messages = [
        EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_SUCCESS       => "The operation was successful.",
        EndSponsoringFutureReservesResultCode::END_SPONSORING_FUTURE_RESERVES_NOT_SPONSORED => "Source account is not sponsored.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof EndSponsoringFutureReservesResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
