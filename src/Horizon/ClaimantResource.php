<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Horizon;

class ClaimantResource extends Resource
{
    /**
     * The account Id who can claim the balance.
     *
     * @return string|null
     */
    public function getDestination(): ?string
    {
        return $this->payload->getString('destination');
    }

    /**
     * The condition that must be satisfied so the `destination` can claim the balance.
     *
     * @return PredicateResource|null
     */
    public function getPredicate(): ?PredicateResource
    {
        if ($predicate = $this->payload->getArray('predicate')) {
            return PredicateResource::wrap($predicate);
        }

        return null;
    }
}
