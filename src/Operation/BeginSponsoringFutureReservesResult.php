<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class BeginSponsoringFutureReservesResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return BeginSponsoringFutureReservesResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS           => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED         => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED => XDR::VOID,
            BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE         => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $beginSponsoringFutureReservesResult = new static();
        $beginSponsoringFutureReservesResult->discriminator = BeginSponsoringFutureReservesResultCode::success();
        $beginSponsoringFutureReservesResult->value = null;

        return $beginSponsoringFutureReservesResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof BeginSponsoringFutureReservesResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status.
     *
     * @param BeginSponsoringFutureReservesResultCode $discriminator
     * @return static
     */
    public static function simulate(BeginSponsoringFutureReservesResultCode $discriminator): static
    {
        $beginSponsoringFutureReservesResult = new static();
        $beginSponsoringFutureReservesResult->discriminator = $discriminator;
        $beginSponsoringFutureReservesResult->value = null;

        return $beginSponsoringFutureReservesResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof BeginSponsoringFutureReservesResultCode
            && $this->discriminator->getType() == BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof BeginSponsoringFutureReservesResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#begin-sponsoring-future-reserves
     * @var array<string, string>
     */
    protected $messages = [
        BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_SUCCESS           => "The operation was successful.",
        BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_MALFORMED         => "Source account is equal to sponsoredID.",
        BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_ALREADY_SPONSORED => "Source account is already sponsoring sponsoredID.",
        BeginSponsoringFutureReservesResultCode::BEGIN_SPONSORING_FUTURE_RESERVES_RECURSIVE         => "Either source account is currently being sponsored, or sponsoredID is sponsoring another account.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof BeginSponsoringFutureReservesResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
