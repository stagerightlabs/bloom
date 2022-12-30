<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class ManageOfferEffect extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const MANAGE_OFFER_CREATED = 'manageOfferCreated';
    public const MANAGE_OFFER_UPDATED = 'manageOfferUpdated';
    public const MANAGE_OFFER_DELETED = 'manageOfferDeleted';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::MANAGE_OFFER_CREATED,
            1 => self::MANAGE_OFFER_UPDATED,
            2 => self::MANAGE_OFFER_DELETED,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getEffect(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as MANAGE_OFFER_CREATED.
     *
     * @return static
     */
    public static function created(): static
    {
        return (new static())->withValue(self::MANAGE_OFFER_CREATED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_OFFER_UPDATED.
     *
     * @return static
     */
    public static function updated(): static
    {
        return (new static())->withValue(self::MANAGE_OFFER_UPDATED);
    }

    /**
     * Create a new instance pre-selected as MANAGE_OFFER_DELETED.
     *
     * @return static
     */
    public static function deleted(): static
    {
        return (new static())->withValue(self::MANAGE_OFFER_DELETED);
    }
}
