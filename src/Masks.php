<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom;

class Masks
{
    // Account flag bit masks
    public const MASK_ACCOUNT_FLAGS = 0x7;
    public const MASK_ACCOUNT_FLAGS_V17 = 0xF;

    // Trustline flag bit masks
    public const MASK_TRUSTLINE_FLAGS = 1;
    public const MASK_TRUSTLINE_FLAGS_V13 = 3;
    public const MASK_TRUSTLINE_FLAGS_V17 = 7;

    // Offer entry bit mask
    public const MASK_OFFERENTRY_FLAGS = 1;

    // Claimable balance bit mask
    public const MASK_CLAIMABLE_BALANCE_FLAGS = 1;

    // Ledger header bit mask
    public const MASK_LEDGER_HEADER_FLAGS = 7;
}
