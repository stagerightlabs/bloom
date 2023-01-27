<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class LiquidityPoolWithdrawOperationResource extends OperationResource
{
    /**
     * The liquidity pool associated with this withdrawal.
     *
     * @return string|null
     */
    public function getLiquidityPoolId(): ?string
    {
        return $this->payload->getString('liquidity_pool_id');
    }

    /**
     * An array of objects corresponding to the minimum amount of each
     * reserve that should have been withdrawn.
     *
     * @return array<ReservesResource>
     */
    public function getReservesMinimum(): array
    {
        return array_map(function ($reserve) {
            return ReservesResource::wrap($reserve);
        }, $this->payload->getArray('reserves_max') ?? []);
    }

    /**
     * The number of shares that were redeemed for this withdrawal operation.
     *
     * @return ScaledAmount|null
     */
    public function getShares(): ?ScaledAmount
    {
        if ($shares = $this->payload->getString('shares')) {
            return ScaledAmount::of($shares);
        }

        return null;
    }

    /**
     * An array of objects representing how much of each reserve ended up
     * actually withdrawn from the pool
     *
     * @return array<ReservesResource>
     */
    public function getReservesReceived(): array
    {
        return array_map(function ($reserve) {
            return ReservesResource::wrap($reserve);
        }, $this->payload->getArray('reserves_received') ?? []);
    }
}
