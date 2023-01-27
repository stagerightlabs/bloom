<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class ReservesResource extends Resource
{
    /**
     * Get the asset in canonical `code:issuer` form.
     *
     * @return string|null
     */
    public function getAsset(): ?string
    {
        return $this->payload->getString('asset');
    }

    /**
     * The amount of asset in the reserve.
     *
     * @return ScaledAmount|null
     */
    public function getAmount(): ?ScaledAmount
    {
        if ($amount = $this->payload->getString('amount')) {
            return ScaledAmount::of($amount);
        }

        return null;
    }
}
