<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Transaction;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int64;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class InnerTransactionResult implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Int64 $feeCharged;
    protected InnerTransactionResultResult $result;
    protected InnerTransactionResultExt $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->feeCharged)) {
            $this->feeCharged = Int64::of(0);
        }

        if (!isset($this->result)) {
            throw new InvalidArgumentException('The inner transaction result is missing a inner transaction result result');
        }

        if (!isset($this->ext)) {
            $this->ext = InnerTransactionResultExt::empty();
        }

        $xdr->write($this->feeCharged)
            ->write($this->result)
            ->write($this->ext);
    }

    /**
     * Read the value from XDR.
     *
     * @param XDR $xdr
     * @return static
     */
    public static function newFromXdr(XDR &$xdr): static
    {
        $innerTransactionResult = new static();
        $innerTransactionResult->feeCharged = $xdr->read(Int64::class);
        $innerTransactionResult->result = $xdr->read(InnerTransactionResultResult::class);
        $innerTransactionResult->ext = $xdr->read(InnerTransactionResultExt::class);

        return $innerTransactionResult;
    }

    /**
     * Get the inner transaction result result.
     *
     * @return InnerTransactionResultResult
     */
    public function getResult(): InnerTransactionResultResult
    {
        return $this->result;
    }

    /**
     * Accept an inner transaction result result.
     *
     * @param InnerTransactionResultResult $result
     * @return static
     */
    public function withResult(InnerTransactionResultResult $result): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->result = Copy::deep($result);

        return $clone;
    }
}
