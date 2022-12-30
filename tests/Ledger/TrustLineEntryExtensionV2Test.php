<?php

declare(strict_types=1);

namespace StageRightLabs\Bloom\Tests\Ledger;

use StageRightLabs\Bloom\Exception\InvalidArgumentException;
use StageRightLabs\Bloom\Ledger\TrustLineEntryExtensionV2;
use StageRightLabs\Bloom\Ledger\TrustLineEntryExtensionV2Ext;
use StageRightLabs\Bloom\Primitives\Int32;
use StageRightLabs\Bloom\Tests\TestCase;
use StageRightLabs\PhpXdr\XDR;

/**
 * @coversDefaultClass \StageRightLabs\Bloom\Ledger\TrustLineEntryExtensionV2
 */
class TrustLineEntryExtensionV2Test extends TestCase
{
    /**
     * @test
     * @covers ::toXdr
     */
    public function it_can_be_converted_to_xdr()
    {
        $trustLineEntryExtensionV2 = (new TrustLineEntryExtensionV2())
            ->withLiquidityPoolUseCount(Int32::of(1));
        $buffer = XDR::fresh()->write($trustLineEntryExtensionV2);

        $this->assertEquals('AAAAAQAAAAA=', $buffer->toBase64());
    }

    /**
     * @test
     * @covers ::toXdr
     */
    public function a_liquidity_pool_use_count_is_required_for_xdr_conversion()
    {
        $this->expectException(InvalidArgumentException::class);
        XDR::fresh()->write(new TrustLineEntryExtensionV2());
    }

    /**
     * @test
     * @covers ::newFromXdr
     */
    public function it_can_be_read_from_xdr()
    {
        $trustLineEntryExtensionV2 = XDR::fromBase64('AAAAAQAAAAA=')
            ->read(TrustLineEntryExtensionV2::class);

        $this->assertInstanceOf(TrustLineEntryExtensionV2::class, $trustLineEntryExtensionV2);
        $this->assertInstanceOf(Int32::class, $trustLineEntryExtensionV2->getLiquidityPoolUseCount());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolUseCount
     * @covers ::getLiquidityPoolUseCount
     */
    public function it_accepts_an_int32_liquidity_pool_use_count()
    {
        $trustLineEntryExtensionV2 = (new TrustLineEntryExtensionV2())
            ->withLiquidityPoolUseCount(Int32::of(1));

        $this->assertInstanceOf(Int32::class, $trustLineEntryExtensionV2->getLiquidityPoolUseCount());
    }

    /**
     * @test
     * @covers ::withLiquidityPoolUseCount
     * @covers ::getLiquidityPoolUseCount
     */
    public function it_accepts_a_native_int_liquidity_pool_use_count()
    {
        $trustLineEntryExtensionV2 = (new TrustLineEntryExtensionV2())
            ->withLiquidityPoolUseCount(1);

        $this->assertInstanceOf(Int32::class, $trustLineEntryExtensionV2->getLiquidityPoolUseCount());
    }

    /**
     * @test
     * @covers ::withExtension
     * @covers ::getExtension
     */
    public function it_accepts_an_extension()
    {
        $trustLineEntryExtensionV2 = (new TrustLineEntryExtensionV2())
            ->withExtension(TrustLineEntryExtensionV2Ext::empty());

        $this->assertInstanceOf(TrustLineEntryExtensionV2Ext::class, $trustLineEntryExtensionV2->getExtension());
    }
}
