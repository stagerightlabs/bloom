<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Service;

final class ClaimableBalanceService extends Service
{
    /**
     * Create a new claimant from an addressable. If no predicate is provided
     * it will use the 'unconditional' option.
     *
     * @param Addressable|string $account
     * @param ClaimPredicate|null $claimPredicate
     * @return Claimant
     */
    public function claimant(Addressable|string $account, ClaimPredicate $claimPredicate = null): Claimant
    {
        return Claimant::fromAddressable($account, $claimPredicate);
    }
}
