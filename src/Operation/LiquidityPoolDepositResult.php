<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\Bloom\Utility\Text;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class LiquidityPoolDepositResult extends Union implements XdrUnion, OperationOutcome
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return LiquidityPoolDepositResultCode::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS        => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_MALFORMED      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NO_TRUST       => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED    => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE      => XDR::VOID,
            LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_POOL_FULL      => XDR::VOID,
        ];
    }

    /**
     * Create a new 'success' instance.
     *
     * @return static
     */
    public static function success(): static
    {
        $liquidityPoolDepositResult = new static();
        $liquidityPoolDepositResult->discriminator = LiquidityPoolDepositResultCode::success();
        $liquidityPoolDepositResult->value = null;

        return $liquidityPoolDepositResult;
    }

    /**
     * Return the result type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolDepositResultCode) {
            return $this->discriminator->getType();
        }

        return null;
    }

    /**
     * Simulate a result with a given status and value.
     *
     * @param LiquidityPoolDepositResultCode $code
     * @return static
     */
    public static function simulate(LiquidityPoolDepositResultCode $code): static
    {
        $liquidityPoolDepositResult = new static();
        $liquidityPoolDepositResult->discriminator = $code;
        $liquidityPoolDepositResult->value = null;

        return $liquidityPoolDepositResult;
    }

    /**
     * Was the operation successful?
     *
     * @return bool
     */
    public function wasSuccessful(): bool
    {
        return isset($this->discriminator)
            && $this->discriminator instanceof LiquidityPoolDepositResultCode
            && $this->discriminator->getType() == LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS;
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
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolDepositResultCode) {
            return $this->messages[$this->discriminator->getType()];
        }

        return null;
    }

    /**
     * Error code explanations.
     *
     * @see https://developers.stellar.org/docs/fundamentals-and-concepts/list-of-operations#liquidity-pool-deposit
     * @var array<string, string>
     */
    protected $messages = [
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_SUCCESS        => "The operation was successful.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_MALFORMED      => "One or more of the inputs to the operation was malformed.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NO_TRUST       => "No trustline exists for one of the assets being deposited.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_NOT_AUTHORIZED => "The account does not have authorization for one of the assets.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_UNDERFUNDED    => "There is not enough balance of one of the assets to perform the deposit.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_LINE_FULL      => "The pool share trustline does not have a sufficient limit.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_BAD_PRICE      => "The deposit price is outside of the given bounds.",
        LiquidityPoolDepositResultCode::LIQUIDITY_POOL_DEPOSIT_POOL_FULL      => "The liquidity pool reserves are full.",
    ];

    /**
     * Return the error code.
     *
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof LiquidityPoolDepositResultCode) {
            return Text::snakeCase($this->discriminator->getType());
        }

        return null;
    }
}
