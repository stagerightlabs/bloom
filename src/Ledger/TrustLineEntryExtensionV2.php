<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Utility\Copy;
use StageRightLabs\Bloom\Utility\NoChanges;
use StageRightLabs\Bloom\Utility\NoConstructor;
use StageRightLabs\PhpXdr\Interfaces\XdrStruct;
use StageRightLabs\PhpXdr\XDR;

final class TrustLineEntryExtensionV2 implements XdrStruct
{
    /**
     * Properties
     */
    use NoChanges;
    use NoConstructor;
    protected Int32 $liquidityPoolUseCount;
    protected TrustLineEntryExtensionV2Ext $ext;

    /**
     * Write the value as XDR.
     *
     * @param XDR $xdr
     * @return void
     */
    public function toXdr(XDR &$xdr): void
    {
        if (!isset($this->liquidityPoolUseCount)) {
            throw new InvalidArgumentException('The trust line entry extension V2 is missing a liquidity pool use count');
        }

        if (!isset($this->ext)) {
            $this->ext = TrustLineEntryExtensionV2Ext::empty();
        }

        $xdr->write($this->liquidityPoolUseCount)
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
        $trustLineEntryExtensionV2 = new static();
        $trustLineEntryExtensionV2->liquidityPoolUseCount = $xdr->read(Int32::class);
        $trustLineEntryExtensionV2->ext = $xdr->read(TrustLineEntryExtensionV2Ext::class);

        return $trustLineEntryExtensionV2;
    }

    /**
     * Get the liquidity pool use count.
     *
     * @return Int32
     */
    public function getLiquidityPoolUseCount(): Int32
    {
        return $this->liquidityPoolUseCount;
    }

    /**
     * Accept a liquidity pool use count.
     *
     * @param Int32|int $liquidityPoolUseCount
     * @return static
     */
    public function withLiquidityPoolUseCount(Int32|int $liquidityPoolUseCount): static
    {
        if (is_int($liquidityPoolUseCount)) {
            $liquidityPoolUseCount = Int32::of($liquidityPoolUseCount);
        }

        /** @var static */
        $clone = Copy::deep($this);
        $clone->liquidityPoolUseCount = Copy::deep($liquidityPoolUseCount);

        return $clone;
    }

    /**
     * Get the extension.
     *
     * @return TrustLineEntryExtensionV2Ext
     */
    public function getExtension(): TrustLineEntryExtensionV2Ext
    {
        return $this->ext;
    }

    /**
     * Accept an extension.
     *
     * @param TrustLineEntryExtensionV2Ext $ext
     * @return static
     */
    public function withExtension(TrustLineEntryExtensionV2Ext $ext): static
    {
        /** @var static */
        $clone = Copy::deep($this);
        $clone->ext = Copy::deep($ext);

        return $clone;
    }
}
