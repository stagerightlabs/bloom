<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\ClaimableBalance\ClaimableBalanceId;
use StageRightLabs\Bloom\Cryptography\Hash;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class LedgerKeyClaimableBalance implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected ClaimableBalanceId $balanceId;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->balanceId)) {
            throw new InvalidArgumentException('The ledger key claimable balance is missing a balance Id');
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
        $ledgerKeyClaimableBalance = new static();
        $ledgerKeyClaimableBalance->balanceId = $xdr->read(ClaimableBalanceId::class);

        return $ledgerKeyClaimableBalance;
    }

    /**
     * Get the claimable balance Id.
     *
     * @return ClaimableBalanceId
     */
    public function getClaimableBalanceId(): ClaimableBalanceId
    {
        return $this->balanceId;
    }

    /**
     * Accept a claimable balance Id.
     *
     * @param ClaimableBalanceId|Hash|string $balanceId
     * @return static
     */
    public function withClaimableBalanceId(ClaimableBalanceId|Hash|string $balanceId): static
    {
        if (is_string($balanceId)) {
            $balanceId = ClaimableBalanceId::wrapHash(Hash::fromHex(ltrim($balanceId, '0')));
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->balanceId = $balanceId instanceof Hash
            ? ClaimableBalanceId::wrapHash(Hash::wrap($balanceId))
            : Copy::deep($balanceId);


        return $clone;
    }
}
