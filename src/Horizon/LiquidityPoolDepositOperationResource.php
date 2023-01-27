<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class LiquidityPoolDepositOperationResource extends OperationResource
{
    /**
     * The liquidity pool associated with this deposit.
     *
     * @return string|null
     */
    public function getLiquidityPoolId(): ?string
    {
        return $this->payload->getString('liquidity_pool_id');
    }

    /**
     * An array of objects corresponding to the maximum amount of each
     * reserve that could have been deposited.
     *
     * @return array<ReservesResource>
     */
    public function getReservesMaximum(): array
    {
        return array_map(function ($reserve) {
            return ReservesResource::wrap($reserve);
        }, $this->payload->getArray('reserves_max') ?? []);
    }

    /**
     * A floating point value encoded as a string indicating the minimum
     * exchange rate for this deposit operation.
     *
     * @return string|null
     */
    public function getMinimumPriceString(): ?string
    {
        return $this->payload->getString('min_price');
    }

    /**
     * The numerator of the precise fractional representation of the minimum
     * exchange rate for this deposit operation.
     *
     * @return int|null
     */
    public function getMinimumPriceNumerator(): ?int
    {
        return $this->payload->getInteger('min_price_r.n');
    }

    /**
     * The denominator of the precise fractional representation of the minimum
     * exchange rate for this deposit operation.
     *
     * @return int|null
     */
    public function getMinimumPriceDenominator(): ?int
    {
        return $this->payload->getInteger('min_price_r.d');
    }

    /**
     * A floating point value encoded as a string indicating the maximum
     * exchange rate for this deposit operation.
     *
     * @return string|null
     */
    public function getMaximumPriceString(): ?string
    {
        return $this->payload->getString('max_price');
    }

    /**
     * The numerator of the precise fractional representation of the maximum
     * exchange rate for this deposit operation.
     *
     * @return int|null
     */
    public function getMaximumPriceNumerator(): ?int
    {
        return $this->payload->getInteger('max_price_r.n');
    }

    /**
     * The denominator of the precise fractional representation of the maximum
     * exchange rate for this deposit operation.
     *
     * @return int|null
     */
    public function getMaximumPriceDenominator(): ?int
    {
        return $this->payload->getInteger('max_price_r.d');
    }

    /**
     * An array of objects representing how much of each reserve ended up
     * actually deposited into the pool.
     *
     * @return array<ReservesResource>
     */
    public function getReservesDeposited(): array
    {
        return array_map(function ($reserve) {
            return ReservesResource::wrap($reserve);
        }, $this->payload->getArray('reserves_deposited') ?? []);
    }

    /**
     * The number of pool shares received for this deposit.
     *
     * @return ScaledAmount|null
     */
    public function getSharesReceived(): ?ScaledAmount
    {
        if ($shares = $this->payload->getString('shares_received')) {
            return ScaledAmount::of($shares);
        }

        return null;
    }
}
