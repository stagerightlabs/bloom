<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\ClaimableBalance;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class Claimant extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClaimantType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClaimantType::CLAIMANT_TYPE_V0 => ClaimantV0::class,
        ];
    }

    /**
     * Create a new instance by wrapping a ClaimantV0.
     *
     * @param ClaimantV0 $claimantV0
     * @return static
     */
    public static function wrapClaimantV0(ClaimantV0 $claimantV0): static
    {
        $claimant = new static();
        $claimant->discriminator = ClaimantType::v0();
        $claimant->value = $claimantV0;

        return $claimant;
    }

    /**
     * Return the underlying value.
     *
     * @return ClaimantV0|null
     */
    public function unwrap(): ?ClaimantV0
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Create a new claimant from an addressable. If no predicate is provided
     * it will use the 'unconditional' option.
     *
     * @param Addressable|string $account
     * @param ClaimPredicate|null $claimPredicate
     * @return static
     */
    public static function fromAddressable(Addressable|string $account, ClaimPredicate $claimPredicate = null): static
    {
        if (is_null($claimPredicate)) {
            $claimPredicate = ClaimPredicate::unconditional();
        }

        $claimantV0 = (new ClaimantV0())
            ->withAccountId(AccountId::fromAddressable($account))
            ->withPredicate($claimPredicate);

        return self::wrapClaimantV0($claimantV0);
    }

    /**
     * Get the claimant's address
     *
     * @return string|null
     */
    public function getAddress(): ?string
    {
        if (isset($this->value) && $this->value instanceof ClaimantV0) {
            return $this->value->getAccountId()->getAddress();
        }

        return null;
    }
}
