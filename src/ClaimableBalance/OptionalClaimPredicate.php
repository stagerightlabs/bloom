<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Primitives\Optional;
use StageRightLabs\PhpXdr\Interfaces\XdrOptional;

final class OptionalClaimPredicate extends Optional implements XdrOptional
{
    /**
     * The encoding type for the optional value.
     *
     * @return string
     */
    public static function getXdrValueType(): string
    {
        return ClaimPredicate::class;
    }

    /**
     * Instantiate an instance from a claim predicate.
     *
     * @param ClaimPredicate $claimPredicate
     * @return static
     */
    public static function some(ClaimPredicate $claimPredicate): static
    {
        return self::none()->withValue($claimPredicate);
    }

    /**
     * Return the optional muxed account.
     *
     * @return ClaimPredicate|null
     */
    public function unwrap(): ?ClaimPredicate
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
