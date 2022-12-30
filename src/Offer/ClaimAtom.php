<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Offer;

use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class ClaimAtom extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ClaimAtomType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ClaimAtomType::CLAIM_ATOM_TYPE_V0             => ClaimOfferAtomV0::class,
            ClaimAtomType::CLAIM_ATOM_TYPE_ORDER_BOOK     => ClaimOfferAtom::class,
            ClaimAtomType::CLAIM_ATOM_TYPE_LIQUIDITY_POOL => ClaimLiquidityAtom::class,
        ];
    }

    /**
     * Create a new instance that wraps a ClaimOfferAtomV0.
     *
     * @param ClaimOfferAtomV0 $claimOfferAtomV0
     * @return static
     */
    public static function wrapClaimOfferAtomV0(ClaimOfferAtomV0 $claimOfferAtomV0): static
    {
        $claimAtom = new static();
        $claimAtom->discriminator = ClaimAtomType::v0();
        $claimAtom->value = $claimOfferAtomV0;

        return $claimAtom;
    }

    /**
     * Create a new instance that wraps a ClaimOfferAtom.
     *
     * @param ClaimOfferAtom $claimOfferAtom
     * @return static
     */
    public static function wrapClaimOfferAtom(ClaimOfferAtom $claimOfferAtom): static
    {
        $claimAtom = new static();
        $claimAtom->discriminator = ClaimAtomType::orderBook();
        $claimAtom->value = $claimOfferAtom;

        return $claimAtom;
    }

    /**
     * Create a new instance that wraps a ClaimOfferAtomV0.
     *
     * @param ClaimLiquidityAtom $claimLiquidityAtom
     * @return static
     */
    public static function wrapClaimLiquidityAtom(ClaimLiquidityAtom $claimLiquidityAtom): static
    {
        $claimAtom = new static();
        $claimAtom->discriminator = ClaimAtomType::liquidityPool();
        $claimAtom->value = $claimLiquidityAtom;

        return $claimAtom;
    }

    /**
     * Return the claim atom value.
     *
     * @return ClaimOfferAtomV0|ClaimOfferAtom|ClaimLiquidityAtom|null
     */
    public function unwrap(): ClaimOfferAtomV0|ClaimOfferAtom|ClaimLiquidityAtom|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
