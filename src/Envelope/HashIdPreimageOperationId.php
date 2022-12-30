<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Envelope;

use StageRightLabs\Bloom\Account\AccountId;
use StageRightLabs\Bloom\Account\Addressable;
use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\UInt32;
use StageRightLabs\Bloom\Transaction\SequenceNumber;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class HashIdPreimageOperationId implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected AccountId $sourceAccount;
    protected SequenceNumber $seqNum;
    protected UInt32 $opNum;

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
            throw new InvalidArgumentException('The hash id preimage operation Id is missing a source account');
        }

        if (!isset($this->seqNum)) {
            throw new InvalidArgumentException('The hash id preimage operation Id is missing a sequence number');
        }

        if (!isset($this->opNum)) {
            throw new InvalidArgumentException('The hash id preimage operation Id is missing an operation number');
        }

        $xdr->write($this->sourceAccount)
            ->write($this->seqNum)
            ->write($this->opNum);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $hashIdPreimageOperationId = new static();
        $hashIdPreimageOperationId->sourceAccount = $xdr->read(AccountId::class);
        $hashIdPreimageOperationId->seqNum = $xdr->read(SequenceNumber::class);
        $hashIdPreimageOperationId->opNum = $xdr->read(UInt32::class);

        return $hashIdPreimageOperationId;
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
}
