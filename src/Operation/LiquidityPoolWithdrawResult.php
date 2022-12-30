<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolWithdrawResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LiquidityPoolWithdrawResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS       => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_MALFORMED     => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_NO_TRUST      => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED   => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL     => XDR::VOID,
            LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $liquidityPoolWithdrawResult = new static();
        $liquidityPoolWithdrawResult->discriminator = LiquidityPoolWithdrawResultCode::success();
        $liquidityPoolWithdrawResult->value = null;

        return $liquidityPoolWithdrawResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolWithdrawResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param LiquidityPoolWithdrawResultCode $code
     * @return static
     */
    public static function simulate(LiquidityPoolWithdrawResultCode $code): static
    {
        $liquidityPoolWithdrawResult = new static();
        $liquidityPoolWithdrawResult->discriminator = $code;
        $liquidityPoolWithdrawResult->value = null;

        return $liquidityPoolWithdrawResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof LiquidityPoolWithdrawResultCode
            && $this->discriminator->getType() == LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolWithdrawResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-withdraw
     * @var array<string, string>
     */
    protected $messages = [
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_SUCCESS       => "The operation was successful.",
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_MALFORMED     => "One or more of the inputs to the operation was malformed.",
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_NO_TRUST      => "There is no trustline for one of the assets.",
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDERFUNDED   => "Insufficient balance for the pool shares.",
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_LINE_FULL     => "The withdrawal would exceed the trustline limit for one of the assets.",
        LiquidityPoolWithdrawResultCode::LIQUIDITY_POOL_WITHDRAW_UNDER_MINIMUM => "Unable to withdraw enough to satisfy the minimum price.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolWithdrawResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
