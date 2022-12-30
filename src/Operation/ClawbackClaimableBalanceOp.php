<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Operation;

use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Account\Thresholds;
use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class ClawbackClaimableBalanceOp implements XdrStruct, OperationVariety
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimableBalanceId $balanceId;

    /**
     * Create a new clawback-claimable-balance operation.
     *
     * @param ClaimableBalanceId|Hash|string $balanceId
     * @param Addressable|string|null $source
     * @return Operation
     */
    public static function operation(
        ClaimableBalanceId|Hash|string $balanceId,
        Addressable|string $source = null,
    ): Operation {
        $clawbackClaimableBalanceOp = (new static())
            ->withBalanceId($balanceId);

        return (new Operation())
            ->withBody(OperationBody::make(OperationType::CLAWBACK_CLAIMABLE_BALANCE, $clawbackClaimableBalanceOp))
            ->withSourceAccount($source);
    }

    /**
     * Does the operation have its expected payload?
     *
     * @return bool
     */
    public function isReady(): bool
    {
        return (isset($this->balanceId) && $this->balanceId instanceof ClaimableBalanceId);
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
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->balanceId)) {
            throw new InvalidArgumentException('The clawback claimable balance operation is missing a balance Id');
        }

        $xdr->write($this->balanceId);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $clawbackClaimableBalanceOp = new static();
        $clawbackClaimableBalanceOp->balanceId = $xdr->read(ClaimableBalanceId::class);

        return $clawbackClaimableBalanceOp;
    }

    /**
     * Get the balance Id.
     *
     * @return ClaimableBalanceId
     */
    public function getBalanceId(): ClaimableBalanceId
    {
        return $this->balanceId;
    }

    /**
     * Accept a balance Id.
     *
     * @param ClaimableBalanceId|Hash|string $balanceId
     * @return static
     */
    public function withBalanceId(ClaimableBalanceId|Hash|string $balanceId): static
    {
        if (is_string($balanceId)) {
            $balanceId = ClaimableBalanceId::wrapHash(Hash::fromHex(ltrim($balanceId, '0')));
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->balanceId = $balanceId instanceof Hash
            ? ClaimableBalanceId::wrapHash($balanceId)
            : Copy::deep($balanceId);

        return $clone;
    }
}
