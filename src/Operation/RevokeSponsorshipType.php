<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Primitives\Enumeration;
use StageRightLabs\PhpXdr\Interfaces\XdrEnum;

final class RevokeSponsorshipType extends Enumeration implements XdrEnum
{
    /**
     * Constants
     */
    public const REVOKE_SPONSORSHIP_LEDGER_ENTRY = 'revokeSponsorshipLedgerEntry';
    public const REVOKE_SPONSORSHIP_SIGNER = 'revokeSponsorshipSigner';

    /**
     * The options available in this enumeration.
     *
     * @return array<int, string>
     */
    public static function getOptions(): array
    {
        return [
            0 => self::REVOKE_SPONSORSHIP_LEDGER_ENTRY,
            1 => self::REVOKE_SPONSORSHIP_SIGNER,
        ];
    }

    /**
     * Return the selected type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue();
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_LEDGER_ENTRY.
     *
     * @return static
     */
    public static function revokeSponsorshipLedgerEntry(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_LEDGER_ENTRY);
    }

    /**
     * Create a new instance pre-selected as REVOKE_SPONSORSHIP_SIGNER.
     *
     * @return static
     */
    public static function revokeSponsorshipSigner(): static
    {
        return (new static())->withValue(self::REVOKE_SPONSORSHIP_SIGNER);
    }
}
