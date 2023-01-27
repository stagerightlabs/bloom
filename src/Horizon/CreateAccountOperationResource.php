<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

use StageRightLabs\Bloom\Primitives\ScaledAmount;

class CreateAccountOperationResource extends OperationResource
{
    /**
     * The amount of XLM sent to the new account as a scaled value.
     *
     * @return ScaledAmount|null
     */
    public function getStartingBalance(): ?ScaledAmount
    {
        if ($balance = $this->payload->getString('starting_balance')) {
            return ScaledAmount::of($balance);
        }

        return null;
    }

    /**
     * The account that funded the new account.
     *
     * @return string|null
     */
    public function getFunderAddress(): ?string
    {
        return $this->payload->getString('funder');
    }

    /**
     * The address of the newly created account.
     *
     * @return string|null
     */
    public function getAccountAddress(): ?string
    {
        return $this->payload->getString('account');
    }
}
