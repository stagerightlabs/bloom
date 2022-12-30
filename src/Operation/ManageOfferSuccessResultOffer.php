<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Ledger\OfferEntry;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;
use StageRightLabs\PhpXdr\XDR;

final class ManageOfferSuccessResultOffer extends Union implements XdrUnion
{
    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return ManageOfferEffect::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            ManageOfferEffect::MANAGE_OFFER_CREATED => OfferEntry::class,
            ManageOfferEffect::MANAGE_OFFER_UPDATED => OfferEntry::class,
            ManageOfferEffect::MANAGE_OFFER_DELETED => XDR::VOID,
        ];
    }

    /**
     * Create a new 'created' instance by wrapping an OfferEntry instance.
     *
     * @param OfferEntry $offerEntry
     * @return static
     */
    public static function wrapCreatedOffer(OfferEntry $offerEntry): static
    {
        $manageOfferSuccessResultOffer = new static();
        $manageOfferSuccessResultOffer->discriminator = ManageOfferEffect::created();
        $manageOfferSuccessResultOffer->value = $offerEntry;

        return $manageOfferSuccessResultOffer;
    }

    /**
     * Create a new 'updated' instance by wrapping an OfferEntry instance.
     *
     * @param OfferEntry $offerEntry
     * @return static
     */
    public static function wrapUpdatedOffer(OfferEntry $offerEntry): static
    {
        $manageOfferSuccessResultOffer = new static();
        $manageOfferSuccessResultOffer->discriminator = ManageOfferEffect::updated();
        $manageOfferSuccessResultOffer->value = $offerEntry;

        return $manageOfferSuccessResultOffer;
    }

    /**
     * Return the underlying offer.
     *
     * @return OfferEntry|null
     */
    public function unwrap(): ?OfferEntry
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }

    /**
     * Return the union type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        if (isset($this->discriminator) && $this->discriminator instanceof ManageOfferEffect) {
            return $this->discriminator->getEffect();
        }

        return null;
    }
}
