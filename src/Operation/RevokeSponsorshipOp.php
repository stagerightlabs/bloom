<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\LedgerKey;
use StageRightLabs\Bloom\Primitives\Union;
use StageRightLabs\PhpXdr\Interfaces\XdrUnion;

final class RevokeSponsorshipOp extends Union implements XdrUnion, OperationVariety
{
    /**
     * Create a new revoke-sponsorship operation.
     *
     * @param LedgerKey|null $ledgerKey
     * @param RevokeSponsorshipOpSigner|null $signer
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        LedgerKey $ledgerKey = null,
        RevokeSponsorshipOpSigner $signer = null,
        Addressable|string $source = null
    ): Operation {
        if (is_null($ledgerKey) && is_null($signer)) {
            throw new InvalidArgumentException('The revoke sponsorship operation requires either a ledger key or a signer; at least one must be provided');
        }

        $revokeSponsorshipOp = is_null($signer)
            ? self::wrapLedgerKey($ledgerKey)
            : self::wrapRevokeSponsorshipOpSigner($signer);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::REVOKE_SPONSORSHIP, $revokeSponsorshipOp))
            ->withSourceAccount($source);
    }

    /**
     * Does this operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return isset($this->value)
            && ($this->value instanceof LedgerKey || $this->value instanceof RevokeSponsorshipOpSigner);
    }

    /**
     * Get the operation's threshold category, either "low", "medium" or "high".
     *
     * @return string
     */
    public function getThreshold(): string
    {
        return Thresholds::CATEGORY_MEDIUM;
    }

    /**
     * What type of discriminator will this union use?
     *
     * @return string
     */
    public static function getXdrDiscriminatorType(): string
    {
        return RevokeSponsorshipType::class;
    }

    /**
     * What are the possible value types for this union?
     *
     * @return array<int|string, string>
     */
    public static function arms(): array
    {
        return [
            RevokeSponsorshipType::REVOKE_SPONSORSHIP_LEDGER_ENTRY => LedgerKey::class,
            RevokeSponsorshipType::REVOKE_SPONSORSHIP_SIGNER       => RevokeSponsorshipOpSigner::class,
        ];
    }

    /**
     * Create a new instance from a ledger key.
     *
     * @param LedgerKey $ledgerKey
     * @return static
     */
    public static function wrapLedgerKey(LedgerKey $ledgerKey): static
    {
        $revokeSponsorshipOp = new static();
        $revokeSponsorshipOp->discriminator = RevokeSponsorshipType::revokeSponsorshipLedgerEntry();
        $revokeSponsorshipOp->value = $ledgerKey;

        return $revokeSponsorshipOp;
    }

    /**
     * Create a new instance from a RevokeSponsorshipOpSigner.
     *
     * @param RevokeSponsorshipOpSigner $revokeSponsorshipOpSigner
     * @return static
     */
    public static function wrapRevokeSponsorshipOpSigner(RevokeSponsorshipOpSigner $revokeSponsorshipOpSigner): static
    {
        $revokeSponsorshipOp = new static();
        $revokeSponsorshipOp->discriminator = RevokeSponsorshipType::revokeSponsorshipSigner();
        $revokeSponsorshipOp->value = $revokeSponsorshipOpSigner;

        return $revokeSponsorshipOp;
    }

    /**
     * Return the underlying object.
     *
     * @return LedgerKey|RevokeSponsorshipOpSigner|null
     */
    public function unwrap(): LedgerKey|RevokeSponsorshipOpSigner|null
    {
        return isset($this->value) && !empty($this->value) ? $this->value : null;
    }
}
