<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Asset\Asset;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\LiquidityPool\PoolId;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class HashIdPreimageRevokeId implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sourceAccount;
    protected SequenceNumber $seqNum;
    protected UInt32 $opNum;
    protected PoolId $liquidityPoolId;
    protected Asset $asset;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @throws InvalidArgumentException
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->sourceAccount)) {
            throw new InvalidArgumentException('The hash Id preimage revoke Id is missing a source account');
        }

        if (!isset($this->seqNum)) {
            throw new InvalidArgumentException('The hash Id preimage revoke Id is missing a sequence number');
        }

        if (!isset($this->opNum)) {
            throw new InvalidArgumentException('The hash Id preimage revoke Id is missing an operation number');
        }

        if (!isset($this->liquidityPoolId)) {
            throw new InvalidArgumentException('The hash Id preimage revoke Id is missing a liquidity pool Id');
        }

        if (!isset($this->asset)) {
            throw new InvalidArgumentException('The hash Id preimage revoke Id is missing an asset');
        }

        $xdr->write($this->sourceAccount)
            ->write($this->seqNum)
            ->write($this->opNum)
            ->write($this->liquidityPoolId)
            ->write($this->asset);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $hashIdPreimageRevokeId = new static();
        $hashIdPreimageRevokeId->sourceAccount = $xdr->read(AccountId::class);
        $hashIdPreimageRevokeId->seqNum = $xdr->read(SequenceNumber::class);
        $hashIdPreimageRevokeId->opNum = $xdr->read(UInt32::class);
        $hashIdPreimageRevokeId->liquidityPoolId = $xdr->read(PoolId::class);
        $hashIdPreimageRevokeId->asset = $xdr->read(Asset::class);

        return $hashIdPreimageRevokeId;
    }

    /**
     * Get the source account.
     *
     * @return AccountId
     */
    public function getSourceAccount(): AccountId
    {
        return $this->sourceAccount;
    }

    /**
     * Accept a source account.
     *
     * @param AccountId|Addressable|string $sourceAccount
     * @return static
     */
    public function withSourceAccount(AccountId|Addressable|string $sourceAccount): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->sourceAccount = AccountId::fromAddressable($sourceAccount);

        return $clone;
    }

    /**
     * Get the sequence number.
     *
     * @return SequenceNumber
     */
    public function getSequenceNumber(): SequenceNumber
    {
        return $this->seqNum;
    }

    /**
     * Accept a sequence number.
     *
     * @param SequenceNumber $seqNum
     * @return static
     */
    public function withSequenceNumber(SequenceNumber $seqNum): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->seqNum = Copy::deep($seqNum);

        return $clone;
    }

    /**
     * Get the operation number.
     *
     * @return UInt32
     */
    public function getOperationNumber(): UInt32
    {
        return $this->opNum;
    }

    /**
     * Accept an operation number.
     *
     * @param UInt32|int $opNum
     * @return static
     */
    public function withOperationNumber(UInt32|int $opNum): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->opNum = UInt32::of($opNum);

        return $clone;
    }

    /**
     * Get the liquidity pool Id.
     *
     * @return PoolId
     */
    public function getLiquidityPoolId(): PoolId
    {
        return $this->liquidityPoolId;
    }

    /**
     * Accept a liquidity pool Id.
     *
     * @param PoolId|string $liquidityPoolId
     * @return static
     */
    public function withLiquidityPoolId(PoolId|string $liquidityPoolId): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->liquidityPoolId = PoolId::wrap($liquidityPoolId);

        return $clone;
    }

    /**
     * Get the asset.
     *
     * @return Asset
     */
    public function getAsset(): Asset
    {
        return $this->asset;
    }

    /**
     * Accept an asset.
     *
     * @param Asset $asset
     * @return static
     */
    public function withAsset(Asset $asset): static
    {
        /** @var static **/
        $clone = Copy::deep($this);
        $clone->asset = Copy::deep($asset);

        return $clone;
    }
}
